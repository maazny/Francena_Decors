<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the contact inquiries assigned to the user.
     */
    public function contacts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contact::class, 'assigned_to');
    }

    /**
     * Get the replies sent by the user.
     */
    public function contactReplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactReply::class, 'user_id');
    }

    /**
     * Get the internal notes written by the user.
     */
    public function contactNotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContactNote::class, 'user_id');
    }

    /**
     * Map many roles assigned to the user.
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Map many direct overriding permissions assigned to the user.
     */
    public function directPermissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission');
    }

    /**
     * Check if user holds specific role identifier keys.
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user holds any of the provided role identifier keys.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    /**
     * Check if user holds a specific authorization permission.
     */
    public function hasPermission(string $permission): bool
    {
        // 1. Super Admin bypass
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // 2. Check direct permission overrides
        if ($this->directPermissions()->where('name', $permission)->exists()) {
            return true;
        }

        // 3. Check roles permissions linkage
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }
}
