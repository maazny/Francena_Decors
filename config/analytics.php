<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Analytics Configurations
    |--------------------------------------------------------------------------
    */

    'enabled' => env('ANALYTICS_ENABLED', true),

    'cache' => [
        'enabled' => env('ANALYTICS_CACHE_ENABLED', true),
        'ttl' => env('ANALYTICS_CACHE_TTL', 3600),
        'key' => 'cms_analytics_dashboard_cache',
    ],

    'snapshots' => [
        'pruning_days' => env('ANALYTICS_PRUNE_SNAPSHOTS_DAYS', null),
        'execution_timeout' => env('ANALYTICS_TIMEOUT_SECONDS', 180),
    ],
];
