<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogService;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;

class ApiLoggingMiddleware
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * ApiLoggingMiddleware constructor.
     */
    public function __construct(ActivityLogService $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Handle an incoming request and track operational execution timings and violations.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);
        $statusCode = $response->getStatusCode();
        $user = $request->user();

        Log::info("API Request: [{$request->method()}] {$request->fullUrl()} | Status: {$statusCode} | Duration: {$duration}ms | User ID: " . ($user?->id ?: 'Guest'));

        if ($statusCode === 429) {
            $this->activityLogger->log([
                'user_id' => $user?->id,
                'module' => 'security',
                'action' => ActivityAction::LOGIN,
                'description' => "API Rate Limit Violation from IP {$request->ip()}",
                'status' => ActivityStatus::FAILED,
            ]);
        } elseif ($statusCode === 403) {
            $this->activityLogger->log([
                'user_id' => $user?->id,
                'module' => 'security',
                'action' => ActivityAction::PERMISSION_CHANGE,
                'description' => "API Permission/Gate Violation on [{$request->method()}] {$request->path()} from IP {$request->ip()}",
                'status' => ActivityStatus::FAILED,
            ]);
        } elseif ($statusCode === 401 && $request->path() !== 'api/v1/login') {
            $this->activityLogger->log([
                'user_id' => null,
                'module' => 'security',
                'action' => ActivityAction::LOGIN,
                'description' => "API Authentication failure on [{$request->method()}] {$request->path()} from IP {$request->ip()}",
                'status' => ActivityStatus::FAILED,
            ]);
        }

        $response->headers->set('X-Response-Time-Ms', $duration);
        $response->headers->set('X-API-Version', 'v1');

        return $response;
    }
}
