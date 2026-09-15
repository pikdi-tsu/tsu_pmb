<?php

namespace App\Http\Controllers;

use App\Helpers\AdminSessionHelper;
use App\Models\Admin\User;
use App\Services\UserSyncService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SsoController extends Controller
{
    /**
     * Melempar User ke TSU Homebase OAuth Authorize
     */
    public function redirect()
    {
        $throttleKey = 'sso-attempt:' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return redirect()->route('loginadmin')
                ->with('error', "Terlalu banyak klik SSO. Tunggu <b id='sso-alert-timer'>$seconds</b> detik.")
                ->with('retry_seconds_sso', $seconds);
        }

        RateLimiter::hit($throttleKey, 60);

        $query = http_build_query([
            'client_id'     => config('app.oauth.authorization.id'),
            'redirect_uri'  => config('app.oauth.authorization.redirect'),
            'response_type' => 'code',
            'scope'         => '',
        ]);

        $homebaseUrl = rtrim(config('app.tsu_homebase.url'), '/');
        return redirect($homebaseUrl . '/oauth/authorize?' . $query);
    }

    /**
     * Menangkap Callback Authorization Code + Tukar Token & Profil
     */
    public function callback(Request $request, UserSyncService $syncer)
    {
        // Cek error dari Homebase
        if ($request->has('error')) {
            if ($request->error === 'access_denied') {
                return redirect()->route('loginadmin')
                    ->with('error', 'Login dibatalkan. Anda menolak memberikan akses.');
            }

            abort(403, 'SSO Error: ' . $request->error_description);
        }

        // Validasi Authorization Code
        if (!$request->code) {
            return redirect()->route('loginadmin')
                ->with('error', '[TSU_SSO_CODE] Login SSO Gagal: Authorization Code tidak ditemukan.');
        }

        $homebaseUrl = rtrim(config('app.tsu_homebase.url'), '/');

        try {
            // Tukar Code jadi Token
            $response = Http::withoutVerifying()
                ->withHeaders(['X-Sync-Secret' => config('app.pikdi.key.sync')])
                ->asForm()
                ->post($homebaseUrl . '/oauth/token', [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => config('app.oauth.authorization.id'),
                    'client_secret' => config('app.oauth.authorization.secret'),
                    'redirect_uri'  => config('app.oauth.authorization.redirect'),
                    'code'          => $request->code,
                ]);

            if ($response->failed()) {
                Log::error("[TSU_SSO_TOKEN_ERR] SSO Token Exchange Failed: " . $response->body());
                return redirect()->route('loginadmin')
                    ->with('error', '[TSU_SSO_TOKEN_ERR] Gagal menukar token dengan server SSO.');
            }

            $accessToken = $response->json()['access_token'] ?? null;
            if (!$accessToken) {
                throw new \Exception("[TSU_SSO_TOKEN_EMPTY] Respon token dari Homebase kosong.");
            }

            // Ambil Data Profil User dari Homebase
            $userResponse = Http::withoutVerifying()
                ->withHeaders(['X-Sync-Secret' => config('app.pikdi.key.sync')])
                ->withToken($accessToken)
                ->acceptJson()
                ->get($homebaseUrl . '/api/v1/profile');

            if ($userResponse->failed()) {
                Log::error("[TSU_SSO_PROFILE_ERR] Gagal mengambil data profil: " . $userResponse->body());
                return redirect()->route('loginadmin')
                    ->with('error', '[TSU_SSO_PROFILE_ERR] Gagal mengambil data profil user.');
            }

            $userData = $userResponse->json();

            try {
                // Sinkronisasi data user & role
                $result = $syncer->handle($userData, $accessToken);
                $user = $result['user'];

                // Simpan token di session
                session(['homebase_access_token' => $accessToken]);

                // Login Auth Laravel
                Auth::login($user);

                // Setup Sesi Lengkap Admin PMB
                AdminSessionHelper::setupSession($user);

                return redirect()->route('admin.dashboard')
                    ->with('alert', [
                        'title'   => 'Sukses',
                        'message' => 'Login Berhasil! Selamat Datang, ' . $user->name,
                        'status'  => 'success'
                    ]);
            } catch (\Exception $e) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if (str_contains($e->getMessage(), '[TSU_')) {
                    return redirect()->route('loginadmin')->with('error', $e->getMessage());
                }

                Log::error("[TSU_SSO_SYNC_FAIL] Gagal proses login: ", [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ]);

                return redirect()->route('loginadmin')
                    ->with('error', '[TSU_SSO_SYNC_FAIL] Terjadi kesalahan saat sinkronisasi data akun.');
            }
        } catch (ConnectionException $e) {
            Log::critical("[TSU_SSO_CONN_REFUSED] Gagal menghubungi Homebase.", [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->view('errors.tsu-error', [
                'title'   => 'Server SSO Tidak Dapat Dihubungi',
                'message' => '[TSU_SSO_CONN_REFUSED] Sistem tidak dapat terhubung ke TSU Homebase. Kemungkinan server sedang down atau ada gangguan jaringan. Silakan coba sesaat lagi.',
                'code'    => 503
            ], 503);
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('loginadmin')
                ->with('error', 'Sesi login SSO kadaluarsa. Silakan coba login ulang.');
        } catch (\Exception $e) {
            Log::critical("[TSU_SSO_CRITICAL] Error fatal di callback SSO: " . $e->getMessage());

            return response()->view('errors.tsu-error', [
                'title'   => 'Terjadi Kesalahan Login',
                'message' => '[TSU_SSO_CRITICAL] Terjadi kesalahan teknis saat memproses login: ' . $e->getMessage(),
                'code'    => 500
            ], 500);
        }
    }
}
