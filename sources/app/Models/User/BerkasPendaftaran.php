<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class BerkasPendaftaran extends Model
{
    /**
     * Tentukan nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'pmb_berkas_pendaftaran';
    
    /**
     * Tentukan primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * Tentukan kolom apa saja yang boleh diisi melalui proses insert/update Eloquent.
     * Kolom created_at dan updated_at tidak perlu dimasukkan karena diurus otomatis oleh Laravel.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'kode_daftar',
        'id_berkas',
        'nama_berkas',
        'status_berkas',
        'keterangan_berkas',
        'nik_validasi_berkas',
        'created_by',
        'updated_by',
    ];

    /**
     * Jika tabel kamu menggunakan kolom created_at dan updated_at standar Laravel,
     * pastikan timestamps bernilai true (defaultnya true).
     */
    public $timestamps = true;
    
    // Nanti kalau butuh, kamu bisa menambahkan relasi ke tabel Master Berkas atau Pendaftaran di sini
}