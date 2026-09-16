<?php

namespace App\Services;

use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSyncService
{
    /**
     * Menangani sinkronisasi user Dosen/Tendik/Admin dari TSU Homebase Vault
     */
    public function handle(array $userData, ?string $accessToken = null, bool $onlyUpdateExisting = false): array
    {
        // 1. FILTER ALLOWED ROLE
        $allowedRoles = config('app.roles.allowed', []);
        if (!empty($allowedRoles)) {
            $incomingRoles = [];
            if (!empty($userData['roles']) && is_array($userData['roles'])) {
                foreach ($userData['roles'] as $role) {
                    $rName = is_string($role) ? $role : ($role['name'] ?? null);
                    if ($rName) {
                        $incomingRoles[] = strtolower($rName);
                    }
                }
            }

            // Fallback single role string
            if (!empty($userData['role']) && is_string($userData['role'])) {
                $incomingRoles[] = strtolower($userData['role']);
            }

            $incomingRoles = array_unique($incomingRoles);
            $allowedRoles = array_map('strtolower', $allowedRoles);

            $hasAccess = !empty(array_intersect($incomingRoles, $allowedRoles));
            $isSuperAdmin = in_array('super admin', $incomingRoles, true)
                || ($userData['email'] ?? '') === config('app.pikdi.email');

            if (!$hasAccess && !$isSuperAdmin) {
                Log::warning("[TSU_DENIED_ACCESS] Akses ditolak untuk user: " . ($userData['email'] ?? 'unknown'), [
                    'incoming' => $incomingRoles,
                    'allowed'  => $allowedRoles
                ]);
                throw new Exception('[TSU_DENIED_ACCESS] AKSES DITOLAK: Role Anda (' . implode(', ', $incomingRoles) . ') tidak diizinkan mengakses panel Admin PMB.');
            }
        }

        // 2. PROSES UPDATE / CREATE USER
        try {
            return User::query()->getConnection()->transaction(function () use ($userData, $accessToken, $onlyUpdateExisting) {
                $ssoId = $userData['id'] ?? $userData['sso_id'] ?? null;
                $email = $userData['email'] ?? null;
                $username = $userData['username'] ?? $userData['nik'] ?? null;

                $user = null;
                if ($ssoId) {
                    $user = User::query()->where('sso_id', $ssoId)->first();
                }

                if (!$user && $email) {
                    $user = User::query()->where('email', $email)->first();
                }

                if (!$user && $username) {
                    $user = User::query()->where('nik', $username)
                        ->orWhere('username', $username)
                        ->first();
                }

                if ($onlyUpdateExisting && !$user) {
                    Log::info("[TSU_USER_SKIP] User tidak ditemukan di lokal: " . ($email ?? ''));
                    throw new Exception('[TSU_USER_SKIP] User PMB tidak ditemukan.');
                }

                $isNewUser = false;
                if (!$user) {
                    $user = new User();
                    $user->password = null;
                    $isNewUser = true;
                }

                if ($ssoId) {
                    $user->sso_id = $ssoId;
                }
                $user->name     = $userData['name'] ?? $user->name ?? $username;
                $user->email    = $email ?? $user->email;
                $user->username = $username ?? $user->username;
                $user->nik      = $userData['nik'] ?? $username ?? $user->nik;

                // Avatar URL parsing
                if (array_key_exists('profile_photo_url', $userData)) {
                    if (empty($userData['profile_photo_url'])) {
                        $user->avatar_url = null;
                    } else {
                        if (str_starts_with($userData['profile_photo_url'], 'http')) {
                            $user->avatar_url = $userData['profile_photo_url'];
                        } else {
                            $homebaseUrl = config('app.tsu_homebase.url');
                            $user->avatar_url = rtrim($homebaseUrl, '/') . '/storage/' . $userData['profile_photo_url'];
                        }
                    }
                }

                // Tentukan privilege PMB jika belum ada
                if (!$user->privilege_pmb) {
                    $incomingRoleNames = [];
                    if (!empty($userData['roles']) && is_array($userData['roles'])) {
                        foreach ($userData['roles'] as $r) {
                            $rName = is_string($r) ? $r : ($r['name'] ?? '');
                            if ($rName) {
                                $incomingRoleNames[] = strtolower($rName);
                            }
                        }
                    }

                    if (in_array('super admin', $incomingRoleNames, true) || $user->email === config('app.pikdi.email')) {
                        $user->privilege_pmb = 'G001'; // Super Admin
                    } else {
                        $user->privilege_pmb = 'G003'; // Admin PMB
                    }
                }

                $user->isactive      = $userData['isactive'] ?? 1;
                $user->last_login_at = now();

                if ($accessToken) {
                    $user->sso_access_token = $accessToken;
                }

                $userDirty = $user->isDirty();
                $user->save();

                // Sinkronisasi Spatie Roles berdasarkan data role dari Homebase
                $roleChanged = $this->syncUserRoles($user, $userData);

                // Sinkronisasi ke data_karyawan (PegawaiModel) jika ada data baru
                $this->syncPegawai($user, $userData);

                return [
                    'user'     => $user,
                    'affected' => $isNewUser || $userDirty || $roleChanged,
                ];
            });
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), '[TSU_')) {
                throw $e;
            }

            Log::error("[TSU_SYS_CRITICAL] Gagal memproses user login: " . ($userData['email'] ?? 'unknown'), [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);

            throw new Exception('[TSU_SYS_CRITICAL] Terjadi gangguan sistem Login di PMB. Silakan hubungi PIKDI!');
        }
    }

    /**
     * Sinkronisasi data pegawai lokal (data_karyawan)
     */
    private function syncPegawai(User $user, array $data): void
    {
        $nik = $user->nik ?? $data['nik'] ?? $data['username'] ?? null;
        if (!$nik) {
            return;
        }

        $pegawai = PegawaiModel::where('nik', $nik)->first();
        if (!$pegawai && !empty($user->email)) {
            $pegawai = PegawaiModel::where('email', $user->email)->first();
        }

        if ($pegawai) {
            // Update email jika belum terisi
            if (empty($pegawai->email) && !empty($user->email)) {
                $pegawai->email = $user->email;
                $pegawai->save();
            }
        }
    }

    /**
     * Logika Sinkronisasi Role Spatie dari Homebase Vault
     */
    private function syncUserRoles(User $user, array $userData): bool
    {
        $incomingRoleNames = [];

        // Normalisasi Data Role dari payload Homebase
        if (!empty($userData['roles']) && is_array($userData['roles'])) {
            foreach ($userData['roles'] as $r) {
                $rName = is_string($r) ? $r : ($r['name'] ?? '');
                if ($rName) {
                    $incomingRoleNames[] = strtolower(trim($rName));
                }
            }
        }

        // Fallback single role string
        if (!empty($userData['role']) && is_string($userData['role'])) {
            $incomingRoleNames[] = strtolower(trim($userData['role']));
        }

        $incomingRoleNames = array_unique($incomingRoleNames);

        // Validasi hanya role yang terdaftar di tabel pmb_roles
        $validLocalRoles = Role::query()
            ->where('guard_name', 'web')
            ->whereIn('name', $incomingRoleNames)
            ->pluck('name')
            ->toArray();

        // Pengaman email pikdi (otomatis super admin)
        if ($user->email === config('app.pikdi.email')) {
            Role::query()->firstOrCreate(['name' => 'super admin', 'guard_name' => 'web'], ['is_identity' => 1]);
            if (!in_array('super admin', $validLocalRoles, true)) {
                $validLocalRoles[] = 'super admin';
            }
        }

        // Pertahankan role lokal buatan modul PMB (is_identity = 0, contoh: admin pmb, panitia pmb)
        $currentRoles = $user->getRoleNames()->toArray();
        $rolesToKeep = [];
        $roleObjects = Role::whereIn('name', $currentRoles)->get()->keyBy('name');

        foreach ($currentRoles as $roleName) {
            $roleModel = $roleObjects->get($roleName);
            if ($roleModel && !$roleModel->is_identity) {
                $rolesToKeep[] = $roleName;
            }
        }

        $finalRoles = array_values(array_unique(array_merge($validLocalRoles, $rolesToKeep)));
        $previousRoles = $user->getRoleNames()->toArray();

        sort($previousRoles);
        sort($finalRoles);

        if ($previousRoles !== $finalRoles) {
            $user->syncRoles($finalRoles);
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            return true;
        }

        return false;
    }
}
