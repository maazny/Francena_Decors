<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/health-check', function () {
    return response()->json(['status' => 'ok']);
});

Route::prefix('v1')->group(base_path('routes/api/v1.php'));
