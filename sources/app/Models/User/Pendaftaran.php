<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pendaftaran extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_pendaftaran';
    protected $primaryKey = 'KodePendaftaran';
    protected $keyType = 'string';

    protected $guarded = [];
    // protected $fillable = [
    //     'nik',
    //     'role_access',
    //     'password',
    //     'created_at',
    //     'created_by',
    //     'updated_at',
    //     'updated_by',
    // ];
    public function biodata()
    {
        return $this->hasOne('App\Models\User\Biodata', 'biodata_id', 'biodata_id');
    }

    public function batch()
    {
        return $this->hasOne('App\Models\MasterData\Master_Batch', 'id', 'batch_daftar');
    }

    public function jalur()
    {
        return $this->hasOne('App\Models\MasterData\Master_JenisPendaftaran', 'id', 'jalur_daftar');
    }

    public function jenisbeasiswa()
    {
        return $this->hasOne('App\Models\MasterData\Master_Beasiswa', 'id', 'beasiswa');
    }

    public function jurusansekolah()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanSekolah', 'id', 'jurusan_sekolah');
    }

    public function prodi1()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanKuliah', 'KodeJurusan', 'pilihan1');
    }

    public function prodi2()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanKuliah', 'KodeJurusan', 'pilihan2');
    }

    public function prodi3()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanKuliah', 'KodeJurusan', 'pilihan3');
    }

    public function jurusanditerima()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanKuliah', 'KodeJurusan', 'jurusan_diterima');
    }

    public function waktukuliah()
    {
        return $this->hasOne('App\Models\MasterData\Master_WaktuKuliah', 'id', 'waktu_kuliah');
    }

    public function bayar()
    {
        return $this->hasMany('App\Models\Transaksi', 'id_referensi', 'KodePendaftaran');
    }

    public function jawaban_peserta()
    {
        return $this->hasMany('App\Models\User\JawabanTest', 'kodependaftaran', 'KodePendaftaran');
    }

    public function jurusan_acc()
    {
        return $this->hasOne('App\Models\MasterData\Master_JurusanKuliah', 'KodeJurusan', 'jurusan_diterima');
    }
}
