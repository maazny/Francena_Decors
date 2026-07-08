<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Disable JSON Resource wrapping to prevent double-nested 'data' fields
        JsonResource::withoutWrapping();

        // Configure Rate Limiting
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $limit = $request->user('sanctum')
                ? config('api.throttle.authenticated', 120)
                : config('api.throttle.public', 60);

            return Limit::perMinute($limit)->by($request->user('sanctum')?->id ?: $request->ip());
        });

        RateLimiter::for('api.auth', function (Request $request) {
            return Limit::perMinute(config('api.throttle.auth_attempts', 10))->by($request->ip());
        });
    }
}
