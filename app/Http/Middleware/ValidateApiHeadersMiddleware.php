<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiHeadersMiddleware
{
    /**
     * Handle an incoming request and validate Accept and Content-Type headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->wantsJson() && !$request->is('api/v1/media/*') && !$request->is('api/v1/backups/*/download')) {
            return response()->json([
                'success' => false,
                'error' => 'Accept header must request application/json.'
            ], 406);
        }

        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $contentType = $request->header('Content-Type');
            if (empty($contentType) || (!str_contains($contentType, 'application/json') && !str_contains($contentType, 'multipart/form-data'))) {
                return response()->json([
                    'success' => false,
                    'error' => 'Unsupported Media Type. Content-Type must be application/json or multipart/form-data.'
                ], 415);
            }
        }

        return $next($request);
    }
}
