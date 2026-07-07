<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoleAssigned
{
    use Dispatchable, SerializesModels;

    public User $user;
    public array $roleIds;

    public function __construct(User $user, array $roleIds)
    {
        $this->user = $user;
        $this->roleIds = $roleIds;
    }
}
