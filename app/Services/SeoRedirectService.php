<?php

namespace App\Services;

use App\Models\SeoRedirect;
use App\Events\RedirectCreated;
use App\Events\RedirectUpdated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SeoRedirectService
{
    /**
     * Create a new redirect entry.
     */
    public function createRedirect(array $data): SeoRedirect
    {
        $data['source_url'] = '/' . trim($data['source_url'], '/');
        
        // Loop Check
        if ($this->detectRedirectLoop($data['source_url'], $data['target_url'])) {
            throw new \InvalidArgumentException('Infinite redirect loop detected for this URL routing structure.');
        }

        $redirect = SeoRedirect::create($data);

        $this->clearRedirectCache();

        event(new RedirectCreated($redirect));

        return $redirect;
    }

    /**
     * Update redirect entry.
     */
    public function updateRedirect(SeoRedirect $redirect, array $data): bool
    {
        if (isset($data['source_url'])) {
            $data['source_url'] = '/' . trim($data['source_url'], '/');
        }

        $source = $data['source_url'] ?? $redirect->source_url;
        $target = $data['target_url'] ?? $redirect->target_url;

        // Loop Check
        if ($this->detectRedirectLoop($source, $target, $redirect->id)) {
            throw new \InvalidArgumentException('Infinite redirect loop detected for this URL routing structure.');
        }

        $redirect->update($data);

        $this->clearRedirectCache();

        event(new RedirectUpdated($redirect));

        return true;
    }

    /**
     * Detect recursive redirect loop and circular targets.
     */
    public function detectRedirectLoop(string $source, string $target, ?int $ignoreId = null, array $visited = []): bool
    {
        $normalizedSource = '/' . trim($source, '/');
        $normalizedTarget = '/' . trim($target, '/');

        if ($normalizedSource === $normalizedTarget) {
            return true;
        }

        if (in_array($normalizedSource, $visited) || in_array($normalizedTarget, $visited)) {
            return true;
        }

        $visited[] = $normalizedSource;

        $nextRedirectQuery = SeoRedirect::active()
            ->where('source_url', $normalizedTarget);

        if ($ignoreId) {
            $nextRedirectQuery->where('id', '!=', $ignoreId);
        }

        $nextRedirect = $nextRedirectQuery->first();

        if ($nextRedirect) {
            return $this->detectRedirectLoop($nextRedirect->source_url, $nextRedirect->target_url, $ignoreId, $visited);
        }

        return false;
    }

    /**
     * Search and match redirects (with wildcard support).
     */
    public function findRedirectMatch(string $path): ?SeoRedirect
    {
        $normalizedPath = '/' . trim($path, '/');

        return Cache::rememberForever('seo_redirect_match_' . md5($normalizedPath), function () use ($normalizedPath) {
            // 1. Exact Match
            $exact = SeoRedirect::active()
                ->where('source_url', $normalizedPath)
                ->first();

            if ($exact) {
                return $exact;
            }

            // 2. Wildcard Match
            $wildcards = SeoRedirect::active()
                ->wildcards()
                ->get();

            foreach ($wildcards as $wildcard) {
                if (Str::is($wildcard->source_url, $normalizedPath)) {
                    return $wildcard;
                }
            }

            return null;
        });
    }

    /**
     * Clear redirect cache entries.
     */
    public function clearRedirectCache(): void
    {
        // For simplicity, we can flush all redirect matches or clear via tags
        // Since sqlite/file cache drivers don't support tags by default,
        // we can clear by specific keys or flush the cache partition if needed.
        // Let's clear our specific redirect cache keys when changes occur.
        Cache::flush(); // Safe catch-all for cache consistency
    }
}
