<?php

namespace App\Events;

use App\Models\Role;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleUpdated
{
    use Dispatchable, SerializesModels;

    public Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}
