<?php

namespace App\Services;

use App\Models\User;
use App\Events\UserRoleAssigned;
use App\Events\UserRoleRemoved;
use Illuminate\Support\Facades\Cache;

class UserRoleService
{
    /**
     * Assign roles array to user.
     */
    public function assignRoles(User $user, array $roleIds): void
    {
        $user->roles()->syncWithoutDetaching($roleIds);

        $this->clearCache();

        event(new UserRoleAssigned($user, $roleIds));
    }

    /**
     * Remove role from user.
     */
    public function removeRole(User $user, int $roleId): void
    {
        $user->roles()->detach($roleId);

        $this->clearCache();

        event(new UserRoleRemoved($user, $roleId));
    }

    /**
     * Sync user roles.
     */
    public function syncRoles(User $user, array $roleIds): void
    {
        // Protected User escalation guard checks can be evaluated here
        $user->roles()->sync($roleIds);

        $this->clearCache();

        event(new UserRoleAssigned($user, $roleIds));
    }

    /**
     * Clear caches.
     */
    protected function clearCache(): void
    {
        Cache::flush();
    }
}
