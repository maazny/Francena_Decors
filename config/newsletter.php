<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Newsletter Double Opt-In
    |--------------------------------------------------------------------------
    |
    | When enabled, users subscribing to the newsletter from the frontend
    | will receive a verification email and stay in a PENDING status
    | until they click the verification link.
    |
    */
    'double_opt_in' => env('NEWSLETTER_DOUBLE_OPT_IN', true),
];
