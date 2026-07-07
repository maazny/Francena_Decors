<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Backup & Restore System Configurations
    |--------------------------------------------------------------------------
    */

    'enabled' => env('BACKUP_ENABLED', true),

    'default_disk' => env('BACKUP_DISK', 'local'),

    'default_backup_type' => 'full',

    'compression_enabled' => env('BACKUP_COMPRESSION', true),

    'encryption_enabled' => env('BACKUP_ENCRYPTION', false),

    'queue_enabled' => env('BACKUP_QUEUE', true),

    'verification_enabled' => env('BACKUP_VERIFY', true),

    'max_parallel_backups' => 1,

    'retain_days' => (int) env('BACKUP_RETENTION_DAYS', 30),

    'retain_backups' => 30,

    'allow_download' => true,

    'allow_restore' => true,

    'cloud_enabled' => env('BACKUP_CLOUD', false),

    'notification_enabled' => true,

    'cleanup_enabled' => true,

    'activity_log_enabled' => env('BACKUP_ACTIVITY_LOG', true),
];
