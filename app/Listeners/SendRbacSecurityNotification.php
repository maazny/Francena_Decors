<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\RbacSecurityAlert;
use Illuminate\Support\Facades\Notification;

class SendRbacSecurityNotification
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $eventName = class_basename($event);
        $isCritical = false;
        $title = "RBAC Security Update";
        $message = "An authorization settings adjustment was performed: {$eventName}.";

        if ($eventName === 'UserRoleAssigned') {
            $isCritical = true;
            $title = "Critical Security: Staff Role Assigned";
            $message = "Administrative roles have been assigned to User #{$event->user->id} ({$event->user->email}).";
        } elseif ($eventName === 'UserRoleRemoved') {
            $isCritical = true;
            $title = "Critical Security: Staff Role Revoked";
            $message = "An administrative role has been removed from User #{$event->user->id} ({$event->user->email}).";
        } elseif ($eventName === 'RoleDeleted') {
            $isCritical = true;
            $title = "Critical Security: Role Deleted";
            $message = "The role '{$event->role->name}' has been deleted from the database.";
        }

        if ($isCritical) {
            $superAdmins = User::whereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            })->get();

            if ($superAdmins->isNotEmpty()) {
                Notification::send($superAdmins, new RbacSecurityAlert($title, $message));
            }
        }
    }
}
