<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    // use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'sso_id',
        'username',
        'name',
        'email',
        'nik',
        'role_access',
        'role_access_pmb',
        'privilege_pmb',
        'password',
        'photo_profile_pmb',
        'photo_profile',
        'avatar_url',
        'sso_access_token',
        'sso_refresh_token',
        'isactive',
        'last_login_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    /**
     * Helper to check if user is considered admin in PMB
     */
    public function isAdmin(): bool
    {
        return in_array($this->privilege_pmb, ['G001', 'G002', 'G003'], true)
            || $this->email === config('app.pikdi.email');
    }
}
