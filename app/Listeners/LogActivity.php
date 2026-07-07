<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogActivity implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $eventName = class_basename($event);
        
        switch ($eventName) {
            case 'SubscriberRegistered':
                Log::info("AUDIT: Subscriber Registered. Email: {$event->subscriber->email}, Source: {$event->subscriber->source->value}");
                break;
            case 'SubscriberVerified':
                Log::info("AUDIT: Subscriber Verified. Email: {$event->subscriber->email}");
                break;
            case 'SubscriberUnsubscribed':
                Log::info("AUDIT: Subscriber Unsubscribed. Email: {$event->subscriber->email}, Reason: " . ($event->reason ?: 'No feedback provided'));
                break;
            case 'CampaignCreated':
                Log::info("AUDIT: Campaign Created. Title: {$event->campaign->title}, ID: {$event->campaign->id}");
                break;
            case 'CampaignScheduled':
                Log::info("AUDIT: Campaign Scheduled. Title: {$event->campaign->title}, Scheduled At: {$event->campaign->scheduled_at}");
                break;
            case 'CampaignStarted':
                Log::info("AUDIT: Campaign Processing Started. Title: {$event->campaign->title}, Group ID: " . ($event->groupId ?: 'All'));
                break;
            case 'CampaignCompleted':
                Log::info("AUDIT: Campaign Dispatch Completed. Title: {$event->campaign->title}");
                break;
            case 'CampaignCancelled':
                Log::info("AUDIT: Campaign Cancelled. Title: {$event->campaign->title}");
                break;
            default:
                Log::info("AUDIT: Newsletter activity event recorded: {$eventName}");
                break;
        }
    }
}
