<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BlogPost;

class BlogPostPolicy
{
    public function viewAny(?User $user): bool
    {
        return $user !== null;
    }

    public function view(?User $user, BlogPost $post): bool
    {
        return $user !== null;
    }

    public function create(?User $user): bool
    {
        return $user !== null;
    }

    public function update(?User $user, BlogPost $post): bool
    {
        return $user !== null;
    }

    public function delete(?User $user, BlogPost $post): bool
    {
        return $user !== null;
    }

    public function restore(?User $user, BlogPost $post): bool
    {
        return $user !== null;
    }
}
