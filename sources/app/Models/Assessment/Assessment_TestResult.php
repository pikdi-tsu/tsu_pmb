<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_TestResult extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_test_result';
    protected $primaryKey = 'id';
    protected $fillable = [
        'attempt_id',
        'hasil_json',
        'catatan_admin',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'isactive',
    ];
}
