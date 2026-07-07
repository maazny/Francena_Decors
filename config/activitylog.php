<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Activity Logging Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('ACTIVITY_LOG_ENABLED', true),

    'queue_logging' => env('ACTIVITY_LOG_QUEUE', false),

    'cache_dashboard' => env('ACTIVITY_LOG_CACHE_DASHBOARD', true),

    'cache_duration' => (int) env('ACTIVITY_LOG_CACHE_DURATION', 3600),

    'retention_days' => (int) env('ACTIVITY_LOG_RETENTION_DAYS', 90),

    /*
    |--------------------------------------------------------------------------
    | Logging Category switches
    |--------------------------------------------------------------------------
    */

    'log_login' => env('ACTIVITY_LOG_LOGIN', true),

    'log_logout' => env('ACTIVITY_LOG_LOGOUT', true),

    'log_failed_login' => env('ACTIVITY_LOG_FAILED_LOGIN', true),

    'log_crud' => env('ACTIVITY_LOG_CRUD', true),

    'log_media' => env('ACTIVITY_LOG_MEDIA', true),

    'log_permissions' => env('ACTIVITY_LOG_PERMISSIONS', true),

    'log_roles' => env('ACTIVITY_LOG_ROLES', true),

    'log_settings' => env('ACTIVITY_LOG_SETTINGS', true),
];
