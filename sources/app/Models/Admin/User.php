<?php

namespace App\Models\Admin;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guard_name = 'web';
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
             || $this->hasRole(['super admin', 'super admin pmb', 'admin', 'admin pmb'])
             || $this->email === config('app.pikdi.email');
     }

    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->avatar_url)) {
            return $this->avatar_url;
        }

        if (!empty($this->photo_profile_pmb)) {
            return url('admin/file/file_photoprofile/' . $this->photo_profile_pmb);
        }

        if (!empty($this->photo_profile)) {
            return url('admin/file/file_photoprofile/' . $this->photo_profile);
        }

        $fallbackName = urlencode($this->name ?? $this->username ?? 'User');
        return "https://ui-avatars.com/api/?name={$fallbackName}&color=094B54&background=D0EEF2&bold=true";
    }

    public function pegawai()
    {
        return $this->belongsTo(PegawaiModel::class, 'nik', 'nik');
    }
}
