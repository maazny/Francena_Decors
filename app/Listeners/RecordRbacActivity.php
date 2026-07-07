<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RecordRbacActivity
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $actor = Auth::user();
        $actorStr = $actor ? "User #{$actor->id} ({$actor->email})" : "System/Console";
        $eventName = class_basename($event);

        $details = [];

        if (isset($event->role)) {
            $details['role'] = [
                'id' => $event->role->id,
                'name' => $event->role->name,
                'label' => $event->role->label,
            ];
        }

        if (isset($event->permission)) {
            $details['permission'] = [
                'id' => $event->permission->id,
                'name' => $event->permission->name,
                'label' => $event->permission->label,
            ];
        }

        if (isset($event->user)) {
            $details['target_user'] = [
                'id' => $event->user->id,
                'email' => $event->user->email,
            ];
        }

        if (isset($event->roleIds)) {
            $details['role_ids'] = $event->roleIds;
        }

        if (isset($event->roleId)) {
            $details['role_id'] = $event->roleId;
        }

        Log::channel('single')->info("RBAC_AUDIT: {$eventName} triggered by {$actorStr}", $details);
    }
}
