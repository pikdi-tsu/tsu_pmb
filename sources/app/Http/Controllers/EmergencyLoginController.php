<?php

namespace App\Http\Controllers;

use App\Helpers\AdminSessionHelper;
use App\Models\Admin\User;
use App\Services\UserSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\MessageBag;

class EmergencyLoginController extends Controller
{
    /**
     * Fitur Login As dari Link Bertanda Tangan TSU Homebase Vault
     */
    public function login(Request $request, UserSyncService $syncer)
    {
        $payloadBase64 = $request->query('payload');
        $timestamp     = $request->query('timestamp');
        $token         = $request->query('signature');

        // Jika diakses langsung tanpa parameter, arahkan ke form Rescue Mode
        if (!$request->has('payload') && !$request->has('signature') && !$request->has('timestamp')) {
            return redirect()->route('rescue');
        }

        if (empty($payloadBase64) || empty($timestamp) || empty($token)) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Parameter Tidak Lengkap',
                'message' => 'Tautan Login Darurat tidak memiliki parameter yang valid (payload, timestamp, signature). Pastikan membuka link langsung dari TSU Homebase Vault.',
                'code'    => 400
            ], 400);
        }

        // Normalisasi jika timestamp dikirim dalam milidetik (13 digit)
        $numericTimestamp = (int) $timestamp;
        if (strlen((string) $timestamp) > 10) {
            $numericTimestamp = (int) ($numericTimestamp / 1000);
        }

        // Cek Kadaluarsa (Toleransi 15 menit / 900 detik)
        $maxAge = (int) config('app.pikdi.emergency_expiry', 900);
        $age = now()->timestamp - $numericTimestamp;

        if ($age < -60) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Link Tidak Valid!',
                'message' => 'Timestamp Link Login Darurat tidak valid. Silakan generate ulang dari TSU Homebase Vault.',
                'code'    => 403
            ], 403);
        }

        if ($age > $maxAge) {
            $ageMinutes = max(1, round($age / 60));
            return response()->view('errors.tsu-error', [
                'title'   => 'Expired Link!',
                'message' => "Link Login Darurat Kadaluarsa ({$ageMinutes} menit yang lalu). Silakan generate ulang dari TSU Homebase Vault.",
                'code'    => 403
            ], 403);
        }

        // Validasi Tanda Tangan (Signature)
        $secret = config('app.pikdi.key.emergency');
        if (!$secret) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Konfigurasi Hilang',
                'message' => 'Server Config Error: Emergency Secret missing.',
                'code'    => 500
            ], 500);
        }

        $expectedToken = hash_hmac('sha256', $payloadBase64 . $timestamp, $secret);
        if (!hash_equals($expectedToken, (string) $token)) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Akses Ditolak!',
                'message' => 'Akses Ditolak! Token signature tidak valid.',
                'code'    => 403
            ], 403);
        }

        // Anti Replay: satu link hanya boleh dipakai sekali selama masa berlakunya
        if (!Cache::add('emergency-login:' . hash('sha256', (string) $token), true, $maxAge + 60)) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Link Sudah Digunakan!',
                'message' => 'Link Login Darurat ini sudah pernah dipakai. Silakan generate ulang dari TSU Homebase Vault.',
                'code'    => 403
            ], 403);
        }

        $jsonPayload = base64_decode($payloadBase64);
        try {
            $userData = json_decode($jsonPayload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Payload Rusak',
                'message' => 'Bad Payload Data from Vault',
                'code'    => 400
            ], 400);
        }

        try {
            // Parameter null karena tidak ada access token OAuth
            $result = $syncer->handle($userData, null);
            $user = $result['user'];

            if (!$user->isactive) {
                throw new \Exception('[TSU_DENIED_ACCESS] Login Ditolak! Akun Anda sedang dinonaktifkan.');
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Inisialisasi Sesi Admin PMB
            AdminSessionHelper::setupSession($user);

            return redirect()->route('admin.dashboard')
                ->with('alert', [
                    'title'   => 'Emergency Login',
                    'message' => 'Login Berhasil via Remote Access Vault sebagai: ' . $user->name,
                    'status'  => 'success'
                ]);
        } catch (\Exception $e) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Emergency Login Gagal',
                'message' => $e->getMessage(),
                'code'    => 403
            ], 403);
        }
    }

    /**
     * Menampilkan Form Rescue Login saat Homebase SSO down
     */
    public function showRescueForm()
    {
        $throttleKey = 'rescue-login:' . request()->ip();
        $rescueSeconds = 0;
        $errorBag = new MessageBag();

        if (session()->has('rescue_block_until')) {
            $timeLeft = session('rescue_block_until') - now()->timestamp;
            if ($timeLeft > 0) {
                $rescueSeconds = $timeLeft;
                $errorBag->add('rescue_key', "SECURITY LOCKDOWN: Tunggu <b class='rescue-timer-display'>$rescueSeconds</b> detik lagi.");
                session()->now('errors', $errorBag);
            } else {
                session()->forget('rescue_block_until');
            }
        } elseif (RateLimiter::attempts($throttleKey) > 0) {
            $attemptsLeft = RateLimiter::retriesLeft($throttleKey, 3);
            $errorBag->add('rescue_key', "Peringatan! Sisa percobaan Anda: <b>$attemptsLeft kali</b> lagi.");
        }

        $data = [
            'title'                   => 'PIKDI Rescue Login',
            'existing_rescue_seconds' => $rescueSeconds,
        ];

        return view('admin::login.rescue-login', $data)->withErrors($errorBag);
    }

    /**
     * Memproses Rescue Login dengan Master Rescue Key
     */
    public function processRescueLogin(Request $request)
    {
        $request->validate([
            'username'   => 'required',
            'rescue_key' => 'required',
        ]);

        $throttleKey = 'rescue-login:' . $request->ip();
        $maxAttempts = 3;

        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            session()->put('rescue_block_until', now()->addSeconds($seconds)->timestamp);

            return back()
                ->withErrors(['rescue_key' => "SECURITY LOCKDOWN: Tunggu <b class='rescue-timer-display'>$seconds</b> detik lagi."])
                ->with('retry_seconds_rescue', $seconds)
                ->withInput($request->only('username'));
        }

        $savedHash = config('app.pikdi.key.rescue');
        if (!$savedHash) {
            return back()->withErrors(['rescue_key' => 'Server Config Error: PIKDI_RESCUE_SECRET hash missing in config.']);
        }

        // Pengecekan Hash Rescue Key
        if (!Hash::check($request->rescue_key, $savedHash)) {
            RateLimiter::hit($throttleKey, 60);

            if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                session()->put('rescue_block_until', now()->addSeconds($seconds)->timestamp);

                return back()
                    ->withErrors(['rescue_key' => "SECURITY LOCKDOWN: Tunggu <b class='rescue-timer-display'>$seconds</b> detik lagi."])
                    ->with('retry_seconds_rescue', $seconds)
                    ->withInput($request->only('username'));
            }

            $attemptsLeft = RateLimiter::retriesLeft($throttleKey, $maxAttempts);
            return back()->withErrors([
                'rescue_key' => "Kunci Akses Salah! Sisa percobaan: <b>$attemptsLeft kali</b> lagi."
            ])->withInput($request->only('username'));
        }

        RateLimiter::clear($throttleKey);
        session()->forget('rescue_block_until');

        return $this->performRescueLogin($request->username);
    }

    /**
     * Helper Eksekusi Rescue Login
     */
    private function performRescueLogin(string $username)
    {
        $user = User::query()->where('username', $username)->first();

        if (!$user) {
            return back()
                ->with('error', '<b>User Tidak Ditemukan!</b> Akun tersebut belum ada di database lokal.')
                ->withErrors(['username' => 'User Tidak Ditemukan!'])
                ->withInput(request()->only('username'));
        }

        if (!$user->isactive) {
            return back()
                ->with('error', '<b>Akses Ditolak!</b> Akun Anda telah dinonaktifkan.')
                ->withErrors(['username' => 'Akun Dinonaktifkan'])
                ->withInput(request()->only('username'));
        }

        Auth::login($user);
        request()->session()->regenerate();
        $user->update(['last_login_at' => now()]);

        // Inisialisasi Sesi Admin PMB
        AdminSessionHelper::setupSession($user);

        return redirect()->route('admin.dashboard')
            ->with('alert', [
                'title'   => 'Rescue Success',
                'message' => "Login Darurat Berhasil via <b>Rescue Mode</b> sebagai: <b>{$user->name}</b>",
                'status'  => 'success'
            ]);
    }
}
