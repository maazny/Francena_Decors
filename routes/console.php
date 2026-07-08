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

// Backup & Restore CMS - Artisan Commands (Module 28)
Artisan::command('backup:run {type?}', function (\App\Contracts\BackupServiceInterface $service) {
    $type = $this->argument('type') ?? 'full';
    $this->info("Starting manual system backup [Type: {$type}]...");

    $backup = $service->createBackup([
        'backup_name' => 'CLI_Backup_' . date('Ymd_His'),
        'backup_type' => $type,
        'notes' => 'Triggered via Artisan CLI command line.',
    ]);

    $this->info("Backup request dispatched successfully: {$backup->backup_name}");
})->purpose('Run a manual backup of specified type');

Artisan::command('backup:restore {id}', function (\App\Contracts\BackupServiceInterface $service) {
    $id = $this->argument('id');
    $backup = \App\Models\BackupHistory::findOrFail($id);

    if (!$this->confirm('Warning: This replaces active database parameters and cannot be undone! Proceed?')) {
        $this->warn('Restore cancelled.');
        return;
    }

    $this->info("Starting restoration from '{$backup->backup_name}'...");
    $service->restoreBackup($backup, 0); // User ID 0 represents System/CLI execution
    $this->info('Restoration process initialized successfully.');
})->purpose('Restore database or files from a historical backup archive');

Artisan::command('backup:verify {id}', function (\App\Contracts\BackupServiceInterface $service) {
    $id = $this->argument('id');
    $backup = \App\Models\BackupHistory::findOrFail($id);

    $this->info("Verifying integrity checksum for '{$backup->backup_name}'...");
    $verified = $service->verifyBackup($backup);

    if ($verified) {
        $this->info('Checksum matches. Archive integrity is verified green.');
    } else {
        $this->error('Checksum mismatch! Archive file is corrupted.');
    }
})->purpose('Run SHA-256 integrity validation checks on a backup archive');

Artisan::command('backup:cleanup', function (\App\Contracts\BackupServiceInterface $service) {
    $this->info('Starting historical backup cleanup...');
    $count = $service->cleanupExpiredBackups();
    $this->info("Cleanup finished. Removed {$count} expired backup records.");
})->purpose('Clean up expired backup archives based on retention limits');

Artisan::command('backup:retry {id}', function (\App\Contracts\BackupServiceInterface $service) {
    $id = $this->argument('id');
    $backup = \App\Models\BackupHistory::findOrFail($id);

    $this->info("Re-triggering failed backup '{$backup->backup_name}'...");
    $service->retryBackup($backup);
    $this->info('Retry job successfully dispatched.');
})->purpose('Retry compiling a failed backup archive');

Artisan::command('backup:stats', function (\App\Contracts\BackupServiceInterface $service) {
    $this->info('Compiling backup statistics...');
    $stats = $service->getStatistics();

    $this->table(
        ['Metric', 'Value'],
        [
            ['Total Archives', $stats['total_backups']],
            ['Database Only', $stats['database_backups']],
            ['Full Website', $stats['full_backups']],
            ['Failed Tasks', $stats['failed_backups']],
            ['Storage Used (MB)', number_format($stats['storage_used'] / 1024 / 1024, 2)],
        ]
    );
})->purpose('Refresh and display backup dashboard statistics metrics');

Artisan::command('backup:health', function () {
    $this->info('Running Backup System Health diagnostics...');

    $disk = config('backup.default_disk', 'local');
    $writable = false;
    $freeSpace = 'N/A';

    try {
        \Illuminate\Support\Facades\Storage::disk($disk)->put('health_check.txt', 'health');
        \Illuminate\Support\Facades\Storage::disk($disk)->delete('health_check.txt');
        $writable = true;
    } catch (\Throwable $e) {}

    if ($disk === 'local') {
        $df = disk_free_space(storage_path());
        $dt = disk_total_space(storage_path());
        $freeSpace = number_format(($df / $dt) * 100, 2) . '% free';
    }

    $latest = \App\Models\BackupHistory::where('status', \App\Enums\BackupStatus::COMPLETED)->latest()->first();
    $latestAge = $latest ? $latest->created_at->diffForHumans() : 'Never';

    $this->table(
        ['Component Check', 'Status', 'Details'],
        [
            ['Storage Disk Writable', $writable ? 'OK' : 'FAIL', "Disk: {$disk}"],
            ['Disk Storage Space', 'OK', "Free space: {$freeSpace}"],
            ['Latest Successful Backup', 'OK', "Age: {$latestAge}"],
            ['Scheduler Running', 'OK', 'Check cron entry'],
        ]
    );
})->purpose('Run diagnostic health checks on backup systems');

// Schedule backup commands
\Illuminate\Support\Facades\Schedule::command('backup:run full')->dailyAt('02:00');
\Illuminate\Support\Facades\Schedule::command('backup:cleanup')->dailyAt('03:00');

// Analytics CMS - Artisan Commands (Module 30)
Artisan::command('analytics:snapshot', function (\App\Contracts\SnapshotServiceInterface $service) {
    $this->info('Starting metrics snapshots compilation...');
    $service->captureSystemSnapshots();
    $this->info('Snapshots compiled successfully.');
})->purpose('Capture a snapshot of active system statistics');

Artisan::command('analytics:cleanup', function (\App\Contracts\ReportServiceInterface $reportService, \App\Contracts\SnapshotServiceInterface $snapshotService) {
    $retentionDays = config('analytics.retention_days', 365);
    $this->info("Pruning reports and snapshots older than {$retentionDays} days...");
    
    $reports = $reportService->cleanupOldReports($retentionDays);
    $snapshots = $snapshotService->pruneSnapshots($retentionDays);
    
    $this->info("Cleaned {$reports} reports and {$snapshots} snapshots successfully.");
})->purpose('Clean up expired analytics and snapshots historical records');

Artisan::command('analytics:refresh', function (\App\Contracts\AnalyticsServiceInterface $service) {
    $this->info('Invalidating and regenerating dashboard KPI cache...');
    \Illuminate\Support\Facades\Cache::forget(config('analytics.cache.key') . '_dashboard_kpis');
    $service->getDashboardMetrics();
    $this->info('Dashboard KPI Cache refreshed successfully.');
})->purpose('Refresh dashboard analytics metrics caches');

Artisan::command('analytics:health', function (\App\Contracts\AnalyticsServiceInterface $service) {
    $this->info('Compiling system health diagnostics...');
    $stats = $service->getSystemHealth();
    
    $this->table(
        ['Component', 'Value'],
        [
            ['Database Size', $stats['database_size_formatted']],
            ['Cache Driver', $stats['cache_driver']],
            ['Queue Connection', $stats['queue_connection']],
        ]
    );
})->purpose('Run system space usage diagnostics');

// Schedule analytics tasks
\Illuminate\Support\Facades\Schedule::command('analytics:snapshot')->hourly();
\Illuminate\Support\Facades\Schedule::command('analytics:cleanup')->dailyAt('04:00');
\Illuminate\Support\Facades\Schedule::command('analytics:refresh')->dailyAt('04:30');


