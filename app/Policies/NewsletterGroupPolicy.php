<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsletterGroup;

class NewsletterGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, NewsletterGroup $group): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, NewsletterGroup $group): bool
    {
        return true;
    }

    public function delete(User $user, NewsletterGroup $group): bool
    {
        return true;
    }
}
