<?php

namespace App\Helpers;

use App\Models\Admin\Admin;
use App\Models\Admin\GroupUserModel;
use App\Models\Admin\MasterGroupModel;
use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Session;

class AdminSessionHelper
{
    /**
     * Setup complete admin session required by PMB Admin module
     *
     * @param User $user
     * @return Admin
     */
    public static function setupSession(User $user): Admin
    {
        // 1. Ambil data pegawai dari data_karyawan (PegawaiModel)
        $nik = $user->nik ?? $user->username;
        $pegawai = null;
        if ($nik) {
            $pegawai = PegawaiModel::where('nik', $nik)->first();
        }
        if (!$pegawai && $user->email) {
            $pegawai = PegawaiModel::where('email', $user->email)->first();
        }

        $nama = $pegawai ? ($pegawai->nama ?? $pegawai->NAMA ?? $user->name) : ($user->name ?? $user->email);
        $finalNik = $pegawai ? ($pegawai->nik ?? $pegawai->NIP ?? $nik) : ($nik ?? 'ADMIN');

        // Pastikan nik di user terisi jika baru sinkron
        if (!$user->nik && $finalNik) {
            $user->update(['nik' => $finalNik]);
        }

        // 2. Tentukan privilege_pmb (G001 = Super Admin, G003 = Admin PMB)
        $privilege = $user->privilege_pmb;
        if (!$privilege) {
            // Default jika belum ada group: jika email pikdi maka G001, jika dosen/tendik berikan G003 (Admin PMB)
            if ($user->email === config('app.pikdi.email') || str_contains(strtolower($user->name ?? ''), 'super admin')) {
                $privilege = 'G001';
            } else {
                $privilege = 'G003';
            }
            $user->update(['privilege_pmb' => $privilege]);
        }

        // 3. Query MasterGroupModel dan GroupUserModel
        $groupuser = GroupUserModel::where('KodeGroupUser', $privilege)->get();
        $mastergroup = MasterGroupModel::where('KodeGroupUser', $privilege)->first();

        // 4. Bangun objek Admin
        $admin = new Admin();
        $admin->nip   = $finalNik;
        $admin->nama  = $nama;
        $admin->email = $user->email;

        // 5. Injeksi ke Session PMB
        Session::put('session', $admin);
        Session::put('namagroup', $mastergroup ? $mastergroup->NamaGroup : 'Admin PMB');
        Session::put('groupuser', $groupuser);
        Session::put('appname', 'PMB');

        // Bersihkan session sementara jika ada
        Session::forget('tmp');
        Session::forget('login_chance');
        Session::forget('time_chance');

        return $admin;
    }
}
