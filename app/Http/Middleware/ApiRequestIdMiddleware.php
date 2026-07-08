<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->header('X-Request-ID') ?: 'req_' . Str::random(16);
        
        $request->attributes->set('request_id', $requestId);

        $response = $next($request);

        if (isset($response->headers)) {
            $response->headers->set('X-Request-ID', $requestId);
        }

        return $response;
    }
}
