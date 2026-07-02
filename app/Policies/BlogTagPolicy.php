<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BlogTag;

class BlogTagPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, BlogTag $tag): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, BlogTag $tag): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, BlogTag $tag): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, BlogTag $tag): bool
    {
        return $user !== null;
    }
}
