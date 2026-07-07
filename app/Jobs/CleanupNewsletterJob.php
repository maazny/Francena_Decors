<?php

namespace App\Jobs;

use App\Models\NewsletterSubscriber;
use App\Models\NewsletterCampaignLog;
use App\Enums\SubscriptionStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Running newsletter database cleanup job...");

        // 1. Permanently delete subscribers soft-deleted more than 30 days ago
        $oldDeletedCount = NewsletterSubscriber::onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subDays(30))
            ->forceDelete();

        // 2. Remove pending verification tokens that have expired (e.g. pending for more than 7 days)
        $unverifiedExpiredCount = NewsletterSubscriber::where('status', SubscriptionStatus::PENDING)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->delete();

        // 3. Cleanup failed campaign log records older than 90 days
        $oldLogsCount = NewsletterCampaignLog::where('delivery_status', 'failed')
            ->where('created_at', '<', Carbon::now()->subDays(90))
            ->delete();

        Log::info("Newsletter database cleanup complete. Removed {$oldDeletedCount} soft-deleted subscribers, {$unverifiedExpiredCount} expired subscriptions, and {$oldLogsCount} failed campaign logs.");
    }
}
