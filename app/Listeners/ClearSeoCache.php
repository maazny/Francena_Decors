<?php

namespace App\Listeners;

use App\Events\SeoUpdated;
use App\Events\SeoPageUpdated;
use App\Events\RedirectCreated;
use App\Events\RedirectUpdated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClearSeoCache
{
    /**
     * Clear all affected SEO caches when changes occur.
     */
    public function handle(object $event): void
    {
        try {
            if ($event instanceof SeoUpdated) {
                Cache::forget('seo_settings');
                Log::info("SEO Settings cache cleared.");
            }

            if ($event instanceof SeoPageUpdated) {
                $page = $event->page;
                Cache::forget('seo_page_slug_' . md5('/' . trim($page->slug, '/')));
                if ($page->page_key) {
                    Cache::forget('seo_page_key_' . $page->page_key);
                }
                Cache::forget('seo_json_ld_page_' . $page->id);
                Log::info("SEO Page cache cleared for slug: {$page->slug}");
            }

            if ($event instanceof RedirectCreated || $event instanceof RedirectUpdated) {
                Cache::flush(); // Flush cache to evict matched routes redirects cache
                Log::info("Redirect mappings changed. Cache cleared.");
            }
        } catch (\Throwable $e) {
            Log::error("Failed to clear SEO cache: " . $e->getMessage());
        }
    }
}
