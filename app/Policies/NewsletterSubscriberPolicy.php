<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, NewsletterSubscriber $subscriber): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, NewsletterSubscriber $subscriber): bool
    {
        return true;
    }

    public function delete(User $user, NewsletterSubscriber $subscriber): bool
    {
        return true;
    }

    public function restore(User $user, NewsletterSubscriber $subscriber): bool
    {
        return true;
    }
}
