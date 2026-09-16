<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\MenuSidebar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PmbMenuSidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. DAFTARKAN ROLE DASAR
        $superAdminRole = Role::firstOrCreate(['name' => 'super admin', 'guard_name' => 'web'], ['is_identity' => 1]);
        $adminPmbRole   = Role::firstOrCreate(['name' => 'admin pmb', 'guard_name' => 'web'], ['is_identity' => 0]);
        $panitiaRole    = Role::firstOrCreate(['name' => 'panitia pmb', 'guard_name' => 'web'], ['is_identity' => 0]);

        // 2. DAFTARKAN PERMISSIONS DASAR STANDAR TSU
        $permissions = [
            // System Permissions
            'system:user:view',
            'system:user:create',
            'system:user:edit',
            'system:user:delete',

            'system:role:view',
            'system:role:create',
            'system:role:edit',
            'system:role:delete',

            'system:permission:view',
            'system:permission:create',
            'system:permission:delete',

            'system:menu:view',
            'system:menu:create',
            'system:menu:edit',
            'system:menu:delete',

            'system:log:view',

            // PMB Permissions
            'pmb:dashboard:view',
            'pmb:datapendaftaran:view',
            'pmb:datapendaftaran:beasiswa',
            'pmb:datapendaftaran:nonbeasiswa',
            'pmb:berkas:view',
            'pmb:pembayaran:view',
            'pmb:pembayaran:pendaftaran',
            'pmb:pembayaran:ukt',
            'pmb:nim:view',
            'pmb:final:view',
            'pmb:assessment:view',
            'pmb:assessment:mastertest',
            'pmb:assessment:mastersoal',
            'pmb:assessment:hasiltest',
            'pmb:assessment:monitoring',
            'pmb:masterdata:view',
            'pmb:masterdata:batch',
            'pmb:masterdata:jalur',
            'pmb:masterdata:ukt',
            'pmb:masterdata:beasiswa',
            'pmb:masterdata:kejuaraan',
            'pmb:masterdata:provinsi',
            'pmb:masterdata:kabupaten',
            'pmb:masterdata:kecamatan',
            'pmb:masterdata:kelurahan',
            'pmb:masterdata:rekomendator',
            'pmb:masterdata:jenisberkas',
            'pmb:masterdata:berkas',
            'pmb:masterdata:fakultas',
            'pmb:masterdata:jurusan',
            'pmb:masterdata:jenjang',
            'pmb:masterdata:jurusansekolah',
            'pmb:masterdata:content',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Berikan semua permission ke admin pmb
        $adminPmbRole->syncPermissions($permissions);

        // 3. SEEDING STRUKTUR MENU SIDEBAR (pmb_menu_sidebars)
        MenuSidebar::truncate();

        // 1. Dashboard
        MenuSidebar::create([
            'name'            => 'Dashboard',
            'icon'            => 'fas fa-tachometer-alt',
            'route'           => 'admin.dashboard',
            'permission_name' => 'pmb:dashboard:view',
            'parent_id'       => null,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        // 2. Data Pendaftaran
        $pendaftaran = MenuSidebar::create([
            'name'            => 'Data Pendaftaran',
            'icon'            => 'fas fa-clipboard-list',
            'route'           => '#',
            'permission_name' => 'pmb:datapendaftaran:view',
            'parent_id'       => null,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Beasiswa',
            'icon'            => 'far fa-circle',
            'route'           => 'admin.databeasiswa.show',
            'permission_name' => 'pmb:datapendaftaran:beasiswa',
            'parent_id'       => $pendaftaran->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Non Beasiswa',
            'icon'            => 'far fa-circle',
            'route'           => 'admin.datanonbeasiswa.show',
            'permission_name' => 'pmb:datapendaftaran:nonbeasiswa',
            'parent_id'       => $pendaftaran->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        // 3. Data Berkas PMB
        MenuSidebar::create([
            'name'            => 'Data Berkas PMB',
            'icon'            => 'fas fa-file',
            'route'           => 'admin.berkaspmb.show',
            'permission_name' => 'pmb:berkas:view',
            'parent_id'       => null,
            'order'           => 3,
            'isactive'        => 1,
        ]);

        // 4. Data Pembayaran PMB
        $pembayaran = MenuSidebar::create([
            'name'            => 'Data Pembayaran PMB',
            'icon'            => 'fas fa-money-check',
            'route'           => '#',
            'permission_name' => 'pmb:pembayaran:view',
            'parent_id'       => null,
            'order'           => 4,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Pendaftaran',
            'icon'            => 'far fa-circle',
            'route'           => 'admin.pembayaranpmb.show',
            'permission_name' => 'pmb:pembayaran:pendaftaran',
            'parent_id'       => $pembayaran->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'UKT',
            'icon'            => 'far fa-circle',
            'route'           => 'admin.pembayaranukt.show',
            'permission_name' => 'pmb:pembayaran:ukt',
            'parent_id'       => $pembayaran->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        // 5. Generate NIM
        MenuSidebar::create([
            'name'            => 'Generate NIM',
            'icon'            => 'fas fa-id-card',
            'route'           => 'admin.nim.index',
            'permission_name' => 'pmb:nim:view',
            'parent_id'       => null,
            'order'           => 5,
            'isactive'        => 1,
        ]);

        // 6. Final PMB
        MenuSidebar::create([
            'name'            => 'Final PMB',
            'icon'            => 'fas fa-user-check',
            'route'           => 'admin.finalpmb.show',
            'permission_name' => 'pmb:final:view',
            'parent_id'       => null,
            'order'           => 6,
            'isactive'        => 1,
        ]);

        // 7. Assessment
        $assessment = MenuSidebar::create([
            'name'            => 'Assessment',
            'icon'            => 'fa fa-list-ul',
            'route'           => '#',
            'permission_name' => 'pmb:assessment:view',
            'parent_id'       => null,
            'order'           => 7,
            'isactive'        => 1,
        ]);

        $subMasterAssess = MenuSidebar::create([
            'name'            => 'Master Assessment',
            'icon'            => 'fas fa-clipboard-list',
            'route'           => '#',
            'permission_name' => 'pmb:assessment:mastertest',
            'parent_id'       => $assessment->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Master Test',
            'icon'            => 'far fa-dot-circle',
            'route'           => 'admin.mastertest.show',
            'permission_name' => 'pmb:assessment:mastertest',
            'parent_id'       => $subMasterAssess->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Master Soal',
            'icon'            => 'far fa-dot-circle',
            'route'           => 'admin.mastersoal.show',
            'permission_name' => 'pmb:assessment:mastersoal',
            'parent_id'       => $subMasterAssess->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        $subHasilAssess = MenuSidebar::create([
            'name'            => 'Hasil & Monitoring',
            'icon'            => 'fa fa-list-alt',
            'route'           => '#',
            'permission_name' => 'pmb:assessment:hasiltest',
            'parent_id'       => $assessment->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Hasil Test',
            'icon'            => 'far fa-dot-circle',
            'route'           => 'admin.testassesment.show',
            'permission_name' => 'pmb:assessment:hasiltest',
            'parent_id'       => $subHasilAssess->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Monitoring Test',
            'icon'            => 'far fa-dot-circle',
            'route'           => 'admin.monitoringassesment.show',
            'permission_name' => 'pmb:assessment:monitoring',
            'parent_id'       => $subHasilAssess->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        // 8. Master Data PMB
        $masterData = MenuSidebar::create([
            'name'            => 'Master Data PMB',
            'icon'            => 'fas fa-database',
            'route'           => '#',
            'permission_name' => 'pmb:masterdata:view',
            'parent_id'       => null,
            'order'           => 8,
            'isactive'        => 1,
        ]);

        $masterItems = [
            ['Master Batch Pendaftaran', 'admin.BatchPendaftaran.show', 'pmb:masterdata:batch'],
            ['Master Jalur Pendaftaran', 'admin.JenisPendaftaran.show', 'pmb:masterdata:jalur'],
            ['Master Tarif UKT', 'admin.TarifUKT.show', 'pmb:masterdata:ukt'],
            ['Master Beasiswa', 'admin.Beasiswa.show', 'pmb:masterdata:beasiswa'],
            ['Master Tingkat Kejuaraan', 'admin.TingkatKejuaraan.show', 'pmb:masterdata:kejuaraan'],
            ['Master Provinsi', 'admin.Provinsi.show', 'pmb:masterdata:provinsi'],
            ['Master Kabupaten/Kota', 'admin.Kabupaten.show', 'pmb:masterdata:kabupaten'],
            ['Master Kecamatan', 'admin.Kecamatan.show', 'pmb:masterdata:kecamatan'],
            ['Master Kelurahan', 'admin.Kelurahan.show', 'pmb:masterdata:kelurahan'],
            ['Master Rekomendator', 'admin.Rekomendator.show', 'pmb:masterdata:rekomendator'],
            ['Master Jenis Berkas', 'admin.JenisBerkas.show', 'pmb:masterdata:jenisberkas'],
            ['Master Berkas', 'admin.Berkas.show', 'pmb:masterdata:berkas'],
            ['Master Fakultas', 'admin.fakultas.show', 'pmb:masterdata:fakultas'],
            ['Master Jurusan', 'admin.Jurusan.show', 'pmb:masterdata:jurusan'],
            ['Master Jenjang Pendidikan', 'admin.Jenjang.show', 'pmb:masterdata:jenjang'],
            ['Master Jurusan Sekolah', 'admin.JurusanSekolah.show', 'pmb:masterdata:jurusansekolah'],
            ['Master Content', 'admin.content.show', 'pmb:masterdata:content'],
        ];

        foreach ($masterItems as $idx => $m) {
            MenuSidebar::create([
                'name'            => $m[0],
                'icon'            => 'far fa-circle',
                'route'           => $m[1],
                'permission_name' => $m[2],
                'parent_id'       => $masterData->id,
                'order'           => $idx + 1,
                'isactive'        => 1,
            ]);
        }

        // 9. System Management (Standar TSU HRIS: Users, Roles, Role Permissions, Menus Management, Log Aktivitas)
        $system = MenuSidebar::create([
            'name'            => 'System Management',
            'icon'            => 'fas fa-cogs',
            'route'           => '#',
            'permission_name' => 'system:view',
            'parent_id'       => null,
            'order'           => 9,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Users',
            'icon'            => 'fas fa-users',
            'route'           => 'admin.system.users.index',
            'permission_name' => 'system:user:view',
            'parent_id'       => $system->id,
            'order'           => 1,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Roles',
            'icon'            => 'fas fa-user-shield',
            'route'           => 'admin.system.roles.index',
            'permission_name' => 'system:role:view',
            'parent_id'       => $system->id,
            'order'           => 2,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Role Permissions',
            'icon'            => 'fas fa-file-shield',
            'route'           => 'admin.system.permissions.index',
            'permission_name' => 'system:permission:view',
            'parent_id'       => $system->id,
            'order'           => 3,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Menus Management',
            'icon'            => 'fas fa-list-ul',
            'route'           => 'admin.system.menus.index',
            'permission_name' => 'system:menu:view',
            'parent_id'       => $system->id,
            'order'           => 4,
            'isactive'        => 1,
        ]);

        MenuSidebar::create([
            'name'            => 'Log Aktivitas',
            'icon'            => 'fas fa-history',
            'route'           => 'admin.log.show',
            'permission_name' => 'system:log:view',
            'parent_id'       => $system->id,
            'order'           => 5,
            'isactive'        => 1,
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
