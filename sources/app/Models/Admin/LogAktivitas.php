<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table      = 'pmb_log_aktivitas';
    protected $primaryKey = 'id';

    public $timestamps = false; // Kita kelola created_at manual

    protected $fillable = [
        'admin_nik',
        'admin_nama',
        'modul',
        'aksi',
        'target_kode',
        'keterangan',
        'created_at',
    ];

    /**
     * Helper statis untuk menyimpan log dengan mudah dari controller manapun.
     */
    public static function catat(string $modul, string $aksi, string $targetKode = '', string $keterangan = ''): void
    {
        try {
            $session = session('session');
            static::insert([
                'admin_nik'    => $session->nip  ?? '-',
                'admin_nama'   => $session->nama ?? '-',
                'modul'        => $modul,
                'aksi'         => $aksi,
                'target_kode'  => $targetKode,
                'keterangan'   => $keterangan,
                'created_at'   => now(),
            ]);
        } catch (\Throwable $e) {
            // Log error tidak boleh mengganggu proses utama
        }
    }
}
