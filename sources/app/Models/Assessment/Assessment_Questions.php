<?php

namespace App\Models\Assessment;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Assessment_Questions extends Model
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'pmb_assessment_questions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tipe_test_id',
        'pertanyaan',
        'dimensi',
        'urutan',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'isactive',
    ];

    public function tipe(){
        return $this->hasOne('App\Models\Assessment\Assessment_TipeTest', 'id','tipe_test_id');
    }

    public function option(){
        return $this->hasMany('App\Models\Assessment\Assessment_QuestionOptions', 'question_id','id');
    }
}
