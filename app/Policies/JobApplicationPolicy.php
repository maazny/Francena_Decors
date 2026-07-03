<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobApplication;

class JobApplicationPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, JobApplication $application): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return true; // candidates can apply publicly without auth
    }

    public function update(?User $user, JobApplication $application): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, JobApplication $application): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, JobApplication $application): bool
    {
        return $user !== null;
    }

    public function forceDelete(?User $user, JobApplication $application): bool
    {
        return $user !== null;
    }
}
