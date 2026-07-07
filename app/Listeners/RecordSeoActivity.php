<?php

namespace App\Listeners;

use App\Events\SeoUpdated;
use App\Events\SeoPageUpdated;
use App\Events\RedirectCreated;
use App\Events\RedirectUpdated;
use App\Events\SitemapGenerated;
use App\Events\RobotsUpdated;
use Illuminate\Support\Facades\Log;

class RecordSeoActivity
{
    /**
     * Record SEO modifications in log trails.
     */
    public function handle(object $event): void
    {
        if ($event instanceof SeoUpdated) {
            Log::info("SEO AUDIT: Global default metadata parameters updated.");
        }

        if ($event instanceof SeoPageUpdated) {
            Log::info("SEO AUDIT: Custom page overrides modified for path location: " . $event->page->slug);
        }

        if ($event instanceof RedirectCreated) {
            Log::info("SEO AUDIT: Redirection route mapped from {$event->redirect->source_url} to {$event->redirect->target_url}.");
        }

        if ($event instanceof RedirectUpdated) {
            Log::info("SEO AUDIT: Redirection route updated to target {$event->redirect->target_url}.");
        }

        if ($event instanceof SitemapGenerated) {
            Log::info("SEO AUDIT: XML Sitemap generated and published to public root: " . $event->sitemapPath);
        }

        if ($event instanceof RobotsUpdated) {
            Log::info("SEO AUDIT: robots.txt sync rules updated.");
        }
    }
}
