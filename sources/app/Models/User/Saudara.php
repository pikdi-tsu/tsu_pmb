<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Saudara extends Model
{
    /**
     * Tentukan nama tabel yang digunakan oleh model ini.
     */
    protected $table = 'pmb_data_saudara';
    
    /**
     * Tentukan primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * Tentukan kolom apa saja yang boleh diisi (sesuai screenshot database-mu).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bio_id',
        'nama',
        'pekerjaan',
        'status_hidup',
        'status_kekerabatan'
    ];

    /**
     * Matikan fitur updated_at otomatis karena tabel tidak memiliki kolom tersebut.
     * Laravel hanya akan mengisi kolom created_at saja secara otomatis.
     */
    const UPDATED_AT = null; 
}