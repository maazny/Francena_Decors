<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleDeleted
{
    use Dispatchable, SerializesModels;

    public int $roleId;
    public string $roleName;

    public function __construct(int $roleId, string $roleName)
    {
        $this->roleId = $roleId;
        $this->roleName = $roleName;
    }
}
