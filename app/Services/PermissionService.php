<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Events\PermissionCreated;
use App\Events\PermissionUpdated;
use App\Events\PermissionAssigned;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    /**
     * Create a permission.
     */
    public function createPermission(array $data): Permission
    {
        $permission = Permission::create($data);

        $this->clearCache();

        event(new PermissionCreated($permission));

        return $permission;
    }

    /**
     * Update permission.
     */
    public function updatePermission(Permission $permission, array $data): bool
    {
        $permission->update($data);

        $this->clearCache();

        event(new PermissionUpdated($permission));

        return true;
    }

    /**
     * Assign permissions array to a Role.
     */
    public function assignPermissionsToRole(Role $role, array $permissionIds): void
    {
        $role->permissions()->sync($permissionIds);

        $this->clearCache();

        event(new PermissionAssigned($role, $permissionIds));
    }

    /**
     * Clear caches.
     */
    protected function clearCache(): void
    {
        Cache::flush();
    }
}
