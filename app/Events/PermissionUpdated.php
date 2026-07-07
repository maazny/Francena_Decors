<?php

namespace App\Events;

use App\Models\Permission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionUpdated
{
    use Dispatchable, SerializesModels;

    public Permission $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
}
