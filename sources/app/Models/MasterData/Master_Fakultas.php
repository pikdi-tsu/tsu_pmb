<?php

namespace App\Models\MasterData;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Fakultas extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_master_fakultas';
    protected $primaryKey = 'IdFakultas';
    // protected $keyType = 'string';
    protected $fillable = [
        'KodeFakultas',
        //     'role_access',
        //     'password',
        //     'created_at',
        //     'created_by',
        //     'updated_at',
        //     'updated_by',
    ];

    function jurusan()
    {
        return $this->hasMany('App\Models\MasterData\Master_JurusanKuliah', 'idfakultas', 'KodeFakultas')->where('isactive', '1');
    }
}
