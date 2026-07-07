<?php

namespace App\Http\Middleware;

use App\Services\SeoRedirectService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoRedirectMiddleware
{
    protected SeoRedirectService $redirectService;

    public function __construct(SeoRedirectService $redirectService)
    {
        $this->redirectService = $redirectService;
    }

    /**
     * Intercept and handle active legacy redirections.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't intercept admin panel requests
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        $path = '/' . trim($request->getPathInfo(), '/');

        try {
            $match = $this->redirectService->findRedirectMatch($path);

            if ($match) {
                // Increment hit count
                $match->increment('hit_count');

                // Write crawler/visitor audit logs
                \App\Models\SeoLog::create([
                    'redirect_id' => $match->id,
                    'url' => $request->fullUrl(),
                    'referrer' => $request->headers->get('referer'),
                    'ip_address' => $request->ip(),
                ]);

                return redirect($match->target_url, $match->type->value);
            }
        } catch (\Throwable $e) {
            // Keep app running in case database tables are missing
        }

        return $next($request);
    }
}
