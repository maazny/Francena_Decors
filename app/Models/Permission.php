<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'permission_group_id',
        'name',
        'label',
        'description',
    ];

    /**
     * Get parent group category container.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }

    /**
     * Map permissions back to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    /**
     * Map permissions back to direct user assignments overrides.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permission');
    }
}
