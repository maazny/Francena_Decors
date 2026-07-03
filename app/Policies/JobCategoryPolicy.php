<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobCategory;

class JobCategoryPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, JobCategory $category): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, JobCategory $category): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, JobCategory $category): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, JobCategory $category): bool
    {
        return $user !== null;
    }

    public function forceDelete(?User $user, JobCategory $category): bool
    {
        return $user !== null;
    }
}
