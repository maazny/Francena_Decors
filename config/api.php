<?php

return [
    /*
    |--------------------------------------------------------------------------
    | REST API Configurations
    |--------------------------------------------------------------------------
    */

    'enabled' => env('API_ENABLED', true),

    'version' => env('API_VERSION', 'v1'),

    'pagination' => [
        'default_per_page' => (int) env('API_DEFAULT_PER_PAGE', 15),
        'max_per_page' => (int) env('API_MAX_PER_PAGE', 100),
    ],

    'cache_enabled' => env('API_CACHE', true),

    'cache_ttl' => (int) env('API_CACHE_TTL', 3600),

    'rate_limit' => env('API_RATE_LIMIT', 60),

    'throttle' => [
        'public' => 60,
        'authenticated' => 120,
        'auth_attempts' => 10,
    ],

    'response_timestamp' => true,

    'request_id' => true,

    'activity_log' => env('API_ACTIVITY_LOG', true),

    'maintenance_mode' => false,
];
