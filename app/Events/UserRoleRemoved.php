<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRoleRemoved
{
    use Dispatchable, SerializesModels;

    public User $user;
    public int $roleId;

    public function __construct(User $user, int $roleId)
    {
        $this->user = $user;
        $this->roleId = $roleId;
    }
}
