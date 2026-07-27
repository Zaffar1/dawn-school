<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string|array $roleSlugs): bool
    {
        if (is_array($roleSlugs)) {
            return in_array($this->role->slug, $roleSlugs);
        }
        return $this->role->slug === $roleSlugs;
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }
}
