<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cache;

class ClearRbacCache
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        // Clear specific user cached bounds if the event carries a user object
        if (isset($event->user)) {
            Cache::forget("user_roles:{$event->user->id}");
            Cache::forget("user_permissions:{$event->user->id}");
        }

        // Force a global cache flush to prevent stale dynamic gate definitions
        Cache::flush();
    }
}
