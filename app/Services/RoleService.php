<?php

namespace App\Services;

use App\Models\Role;
use App\Events\RoleCreated;
use App\Events\RoleUpdated;
use App\Events\RoleDeleted;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    /**
     * Create a new role.
     */
    public function createRole(array $data): Role
    {
        $role = Role::create($data);

        $this->clearCache();

        event(new RoleCreated($role));

        return $role;
    }

    /**
     * Update role.
     */
    public function updateRole(Role $role, array $data): bool
    {
        // Guard system lock roles name editing
        if ($role->is_system && isset($data['name']) && $data['name'] !== $role->name) {
            throw new \InvalidArgumentException('Modifying the identifying name of a system locked role is prohibited.');
        }

        $role->update($data);

        $this->clearCache();

        event(new RoleUpdated($role));

        return true;
    }

    /**
     * Delete role.
     */
    public function deleteRole(Role $role): bool
    {
        // Guard system roles from deletion
        if ($role->is_system) {
            throw new \InvalidArgumentException('System locked roles cannot be deleted.');
        }

        $roleId = $role->id;
        $roleName = $role->name;

        $role->delete();

        $this->clearCache();

        event(new RoleDeleted($roleId, $roleName));

        return true;
    }

    /**
     * Duplicate a role's permissions onto a new role.
     */
    public function duplicateRole(Role $role, string $newRoleName, string $newRoleLabel): Role
    {
        $newRole = Role::create([
            'name' => $newRoleName,
            'label' => $newRoleLabel,
            'is_system' => false,
        ]);

        $permissionIds = $role->permissions()->pluck('id')->toArray();
        $newRole->permissions()->sync($permissionIds);

        $this->clearCache();

        event(new RoleCreated($newRole));

        return $newRole;
    }

    /**
     * Invalidate authorizations cache.
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}
