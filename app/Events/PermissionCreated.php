<?php

namespace App\Events;

use App\Models\Permission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PermissionCreated
{
    use Dispatchable, SerializesModels;

    public Permission $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
}
