<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_Attempts extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_attempts';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kodependaftaran',
        'tipe_test_id',
        'mulai_at',
        'selesai_at',
        'status',
        'created_at',
        'updated_at',
    ];
}
