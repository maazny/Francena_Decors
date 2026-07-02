<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BlogCategory;

class BlogCategoryPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, BlogCategory $category): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, BlogCategory $category): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, BlogCategory $category): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, BlogCategory $category): bool
    {
        return $user !== null;
    }
}
