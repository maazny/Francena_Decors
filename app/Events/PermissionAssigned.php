<?php

namespace App\Events;

use App\Models\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionAssigned
{
    use Dispatchable, SerializesModels;

    public Role $role;
    public array $permissionIds;

    public function __construct(Role $role, array $permissionIds)
    {
        $this->role = $role;
        $this->permissionIds = $permissionIds;
    }
}
