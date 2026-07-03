<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsletterCampaign;

class NewsletterCampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, NewsletterCampaign $campaign): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, NewsletterCampaign $campaign): bool
    {
        return true;
    }

    public function delete(User $user, NewsletterCampaign $campaign): bool
    {
        return true;
    }
}
