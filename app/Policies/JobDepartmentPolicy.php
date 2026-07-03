<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobDepartment;

class JobDepartmentPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, JobDepartment $department): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, JobDepartment $department): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, JobDepartment $department): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, JobDepartment $department): bool
    {
        return $user !== null;
    }

    public function forceDelete(?User $user, JobDepartment $department): bool
    {
        return $user !== null;
    }
}
