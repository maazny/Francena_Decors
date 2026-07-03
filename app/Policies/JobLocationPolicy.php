<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobLocation;

class JobLocationPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, JobLocation $location): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, JobLocation $location): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, JobLocation $location): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, JobLocation $location): bool
    {
        return $user !== null;
    }

    public function forceDelete(?User $user, JobLocation $location): bool
    {
        return $user !== null;
    }
}
