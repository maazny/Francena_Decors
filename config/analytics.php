<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Analytics and Reporting Configurations
    |--------------------------------------------------------------------------
    */

    'enabled' => env('ANALYTICS_ENABLED', true),

    'default_period' => env('ANALYTICS_DEFAULT_PERIOD', 'monthly'),

    'cache_enabled' => env('ANALYTICS_CACHE', true),

    'cache_ttl' => (int) env('ANALYTICS_CACHE_TTL', 3600),

    'snapshot_enabled' => env('ANALYTICS_SNAPSHOT_ENABLED', true),

    'snapshot_interval' => env('ANALYTICS_SNAPSHOT_INTERVAL', 'hourly'),

    'queue_enabled' => env('ANALYTICS_QUEUE', true),

    'retention_days' => env('ANALYTICS_RETENTION_DAYS', 365),

    'max_export_rows' => (int) env('ANALYTICS_MAX_EXPORT_ROWS', 10000),

    'default_chart_type' => 'line',

    'activity_log_enabled' => env('ANALYTICS_ACTIVITY_LOG', true),

    'api_statistics_enabled' => env('ANALYTICS_API_STATS', true),

    'performance_monitoring_enabled' => env('ANALYTICS_PERFORMANCE_MONITOR', true),
];
