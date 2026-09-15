<?php

namespace App\Http\Controllers;

use App\Helpers\AdminSessionHelper;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use App\Services\UserSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Cek Kadaluarsa (Link valid 5 menit)
        if (now()->timestamp - $timestamp > 300) {
            return response()->view('errors.tsu-error', [
                'title'   => 'Expired Link!',
                'message' => 'Link Login Darurat Kadaluarsa. Silakan generate ulang dari TSU Homebase Vault.',
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

            Auth::login($user);

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
        $user = User::query()->where('username', $username)
            ->orWhere('nik', $username)
            ->orWhere('email', $username)
            ->first();

        // Jika user belum ada di tabel users, coba cari di data_karyawan
        if (!$user) {
            $pegawai = PegawaiModel::query()->where('nik', $username)
                ->orWhere('email_kampus', $username)
                ->first();

            if ($pegawai) {
                $user = User::query()->create([
                    'nik'           => $pegawai->nik,
                    'username'      => $pegawai->nik,
                    'name'          => $pegawai->nama ?? $pegawai->NAMA ?? $username,
                    'email'         => $pegawai->email_kampus ?? $pegawai->email_pribadi ?? ($username . '@tsu.ac.id'),
                    'privilege_pmb' => 'G003',
                    'isactive'      => 1,
                ]);
            }
        }

        if (!$user) {
            return back()
                ->with('error', '<b>User Tidak Ditemukan!</b> Akun NIK/Username tersebut belum terdaftar.')
                ->withErrors(['username' => 'User Tidak Ditemukan!'])
                ->withInput(request()->only('username'));
        }

        Auth::login($user);
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
