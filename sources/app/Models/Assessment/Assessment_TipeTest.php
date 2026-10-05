<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_TipeTest extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_tipe_test';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_test',
        'kode_test',
        'tipe_engine',
        'durasi_menit',
        'urutan',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'isactive',
    ];
}
