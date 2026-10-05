<?php

namespace App\Models\MasterData;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Master_Rekomendator extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_master_rekomendator';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'kode_rekomendator',
        'kategori',
        'nama_rekomendator',
        'alamat',
        'pekerjaan',
        'no_hp',
        'email',
        'no_rekening',
        'atasnama_rekening',
        'nama_bank',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'isactive'
    ];
}
