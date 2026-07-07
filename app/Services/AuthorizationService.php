<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthorizationService
{
    /**
     * Dynamically bootstrap permissions as Laravel Gates.
     */
    public function bootstrapGates(): void
    {
        try {
            // Safe boot check to prevent installer or command failures
            if (app()->runningInConsole()) {
                // Keep it fast in CLI or during migrations
                if (!Schema::hasTable('permissions')) {
                    return;
                }
            }

            if (Schema::hasTable('permissions')) {
                $permissions = Permission::all();
                
                foreach ($permissions as $permission) {
                    Gate::define($permission->name, function (\App\Models\User $user) use ($permission) {
                        return $user->hasPermission($permission->name);
                    });
                }
            }
        } catch (\Throwable $e) {
            // Fail silently during initial builds or setup migrations
        }
    }

    /**
     * Generate permission matrix for roles access grid.
     */
    public function getPermissionMatrix(): array
    {
        $groups = PermissionGroup::with('permissions')->get();
        $roles = Role::all();

        $matrix = [];

        foreach ($groups as $group) {
            $groupMatrix = [
                'name' => $group->name,
                'permissions' => [],
            ];

            foreach ($group->permissions as $permission) {
                $roleBindings = [];
                foreach ($roles as $role) {
                    // Check if role has this permission
                    $roleBindings[$role->name] = $role->permissions()->where('permission_id', $permission->id)->exists();
                }

                $groupMatrix['permissions'][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'label' => $permission->label,
                    'roles' => $roleBindings,
                ];
            }

            $matrix[] = $groupMatrix;
        }

        return [
            'roles' => $roles,
            'matrix' => $matrix,
        ];
    }
}
