<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lewati jika tabel sudah ada (database hasil import PMB lawas)
        if (!Schema::hasTable('pmb_log_aktivitas')) {
            Schema::create('pmb_log_aktivitas', static function (Blueprint $table) {
                $table->id();
                $table->string('admin_nik', 30)->default('')->index('idx_log_admin');
                $table->string('admin_nama', 150)->default('');
                $table->string('modul', 60)->default('')->index('idx_log_modul');
                $table->string('aksi', 100)->default('');
                $table->string('target_kode', 60)->default('');
                $table->text('keterangan')->nullable();
                $table->dateTime('created_at')->useCurrent()->index('idx_log_created_at');
            });
        }

        // Daftarkan menu "Log Aktivitas" ke pmb_admin_modul agar bisa dikontrol via Group User & Privilege
        if (Schema::hasTable('pmb_admin_modul')
            && !DB::table('pmb_admin_modul')->where('modul', 'Tools')->where('menu', 'Log Aktivitas')->exists()) {
            DB::table('pmb_admin_modul')->insert([
                'modul'      => 'Tools',
                'menu'       => 'Log Aktivitas',
                'alias'      => 'Log Aktivitas',
                'MenuAktif'  => 'Y',
                'created_at' => now(),
                'created_by' => 'system',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tabel log berisi data audit dari PMB lawas, sengaja tidak di-drop saat rollback
    }
};
