<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'username', 'email', 'password', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        return asset('assets/img/avatars/No_Image_Available.jpg');
    }

    /**
     * Cek apakah user adalah super admin
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles->contains(function ($role) {
            return strtolower($role->name) === 'super admin';
        });
    }

    /**
     * Cek apakah user memiliki permission tertentu
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return $this->hasDirectPermission($permission) || $this->hasPermissionThroughRole($permission);
    }

    /**
     * Cek apakah user memiliki permission melalui role
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermissionThroughRole(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return true;
            }
        }
        return false;
    }
}
