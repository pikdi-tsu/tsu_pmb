<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PegawaiModel extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'data_karyawan';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];
    // protected $fillable = [
    //     'NIP',
    //     'NAMA',
    //     'HOMBASE',
    //     'JENIS KELAMIN',
    //     'TEMPAT LAHIR',
    //     'TANGGAL LAHIR',
    //     'AGAMA',
    //     'NIDN',
    //     'GELAR DEPAN',
    //     'GELAR BELAKANG',
    //     'GOLONGAN/PANGKAT',
    //     'JABATAN FUNGSIONAL',
    //     'JABATAN STRUKTURAL',
    //     'ALAMAT RUMAH',
    //     'NO. TELEPON',
    //     'EMAIL PRIBADI',
    //     'EMAIL KAMPUS',
    // ];
}
