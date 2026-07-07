<?php

namespace App\Services;

use App\Models\SeoPage;
use App\Events\SeoPageUpdated;
use Illuminate\Support\Facades\Cache;

class SeoPageService
{
    /**
     * Create an SEO record for a page.
     */
    public function createSeoPage(array $data): SeoPage
    {
        $page = SeoPage::create($data);
        $this->clearPageCache($page->slug, $page->page_key);
        
        event(new SeoPageUpdated($page));

        return $page;
    }

    /**
     * Update an SEO page configuration.
     */
    public function updateSeoPage(SeoPage $page, array $data): bool
    {
        $oldSlug = $page->slug;
        $oldKey = $page->page_key;

        $page->update($data);

        // Evict caches
        $this->clearPageCache($oldSlug, $oldKey);
        $this->clearPageCache($page->slug, $page->page_key);

        event(new SeoPageUpdated($page));

        return true;
    }

    /**
     * Delete an SEO page configuration.
     */
    public function deleteSeoPage(SeoPage $page): bool
    {
        $slug = $page->slug;
        $key = $page->page_key;

        $page->delete();

        $this->clearPageCache($slug, $key);

        return true;
    }

    /**
     * Duplicate SEO Page properties.
     */
    public function duplicateSeoPage(SeoPage $page, ?string $newSlug = null): SeoPage
    {
        $clone = $page->replicate();
        
        if ($newSlug) {
            $clone->slug = '/' . trim($newSlug, '/');
        } else {
            $clone->slug = $page->slug . '-copy';
        }
        
        $clone->page_key = $page->page_key ? $page->page_key . '_copy_' . time() : null;
        $clone->save();

        return $clone;
    }

    /**
     * Clone global defaults to a page SEO overrides.
     */
    public function cloneDefaultsToPage(SeoPage $page): bool
    {
        $defaults = SeoSettingService::getCached(); // Wait, let's use the model directly
        $settings = \App\Models\SeoSetting::getCached();

        $page->update([
            'title' => $settings->meta_title,
            'meta_description' => $settings->meta_description,
            'meta_keywords' => $settings->meta_keywords,
            'robots' => $settings->robots,
            'og_title' => $settings->og_title,
            'og_description' => $settings->og_description,
            'og_image' => $settings->og_image,
        ]);

        $this->clearPageCache($page->slug, $page->page_key);

        return true;
    }

    /**
     * Clear page cache keys.
     */
    public function clearPageCache(?string $slug = null, ?string $key = null): void
    {
        if ($slug) {
            $normalized = '/' . trim($slug, '/');
            Cache::forget('seo_page_slug_' . md5($normalized));
        }

        if ($key) {
            Cache::forget('seo_page_key_' . $key);
        }
    }
}
