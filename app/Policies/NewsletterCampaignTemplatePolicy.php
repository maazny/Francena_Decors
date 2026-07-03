<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsletterCampaignTemplate;

class NewsletterCampaignTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, NewsletterCampaignTemplate $template): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, NewsletterCampaignTemplate $template): bool
    {
        return true;
    }

    public function delete(User $user, NewsletterCampaignTemplate $template): bool
    {
        return true;
    }
}
