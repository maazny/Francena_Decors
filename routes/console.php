<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activitylog:cleanup', function () {
    $retentionDays = config('activitylog.retention_days', 90);
    $this->info("Pruning activity logs older than {$retentionDays} days...");
    
    $deleted = \App\Models\ActivityLog::where('created_at', '<', now()->subDays($retentionDays))->delete();
    
    $this->info("Pruned {$deleted} log entries successfully.");
})->purpose('Cleanup old activity logs based on retention settings');

Artisan::command('activitylog:stats', function (\App\Contracts\ActivityLogServiceInterface $service) {
    $this->info("Refreshing activity logs statistics cache...");
    \Illuminate\Support\Facades\Cache::forget('activity_logs:statistics');
    $service->getDashboardStatistics();
    $this->info("Statistics cache refreshed successfully.");
})->purpose('Refresh the activity log statistics cache');

// Schedule tasks
\Illuminate\Support\Facades\Schedule::command('activitylog:cleanup')->daily();
\Illuminate\Support\Facades\Schedule::command('activitylog:stats')->hourly();

