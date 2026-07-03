<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobOpening;

class JobOpeningPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, JobOpening $opening): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, JobOpening $opening): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, JobOpening $opening): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, JobOpening $opening): bool
    {
        return $user !== null;
    }

    public function forceDelete(?User $user, JobOpening $opening): bool
    {
        return $user !== null;
    }
}
