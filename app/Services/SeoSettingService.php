<?php

namespace App\Services;

use App\Models\SeoSetting;
use App\Events\SeoUpdated;
use App\Events\RobotsUpdated;
use Illuminate\Support\Facades\Cache;

class SeoSettingService
{
    /**
     * Load global SEO defaults (Cached).
     */
    public function loadGlobalSettings(): SeoSetting
    {
        return SeoSetting::getCached();
    }

    /**
     * Update global settings.
     */
    public function saveGlobalSettings(array $data): SeoSetting
    {
        $settings = SeoSetting::firstOrCreate([]);
        $settings->update($data);

        // Clear settings cache
        SeoSetting::clearCache();

        // Fire Events
        event(new SeoUpdated($settings));

        if (isset($data['robots_txt_rules'])) {
            event(new RobotsUpdated($data['robots_txt_rules']));
        }

        return $settings;
    }

    /**
     * Generate robots.txt rules.
     */
    public function generateRobotsTxt(): string
    {
        return Cache::rememberForever('seo_robots_txt', function () {
            $settings = $this->loadGlobalSettings();
            
            if ($settings->robots_txt_rules) {
                return $settings->robots_txt_rules;
            }

            // Default safe template fallback
            return "User-agent: *\nDisallow: /admin/\nDisallow: /api/\n\nSitemap: " . url('/sitemap.xml');
        });
    }

    /**
     * Clear robots.txt cache.
     */
    public function clearRobotsCache(): void
    {
        Cache::forget('seo_robots_txt');
    }
}
