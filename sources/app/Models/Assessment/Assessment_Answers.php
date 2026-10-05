<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_Answers extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_answers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'attempt_id',
        'question_id',
        'option_id',
        'most_option_id',
        'least_option_id',
        'jawaban_1',
        'jawaban_2',
        'created_at',
        'updated_at',
    ];
}
