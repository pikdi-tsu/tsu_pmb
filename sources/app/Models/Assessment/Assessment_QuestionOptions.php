<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_QuestionOptions extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_question_options';
    protected $primaryKey = 'id';
    protected $fillable = [
        'question_id',
        'label',
        'kode',
        'nilai',
        'is_benar',
        'disc_tipe',
        'urutan',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
