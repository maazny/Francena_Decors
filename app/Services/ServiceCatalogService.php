<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Cache;

class ServiceCatalogService
{
    public const FEATURED_CACHE_KEY = 'services_featured';

    public const CATEGORIES_CACHE_KEY = 'service_categories_active';

    public function featuredServices()
    {
        return Cache::rememberForever(self::FEATURED_CACHE_KEY, function () {
            return Service::with(['category', 'featuredImage'])
                ->active()
                ->featured()
                ->ordered()
                ->take(6)
                ->get();
        });
    }

    public function activeCategories()
    {
        return Cache::rememberForever(self::CATEGORIES_CACHE_KEY, function () {
            return ServiceCategory::with(['featuredImage'])
                ->active()
                ->ordered()
                ->get();
        });
    }

    public function serviceBySlug(string $slug): Service
    {
        return Cache::rememberForever("service_detail_{$slug}", function () use ($slug) {
            return Service::with([
                'category',
                'featuredImage',
                'bannerImage',
                'activeFeatures',
                'activeProcesses',
                'activeFaqs',
            ])->active()->where('slug', $slug)->firstOrFail();
        });
    }

    public function categoryBySlug(string $slug): ServiceCategory
    {
        return Cache::rememberForever("service_category_{$slug}", function () use ($slug) {
            return ServiceCategory::with(['bannerImage', 'featuredImage', 'activeServices.featuredImage'])
                ->active()
                ->where('slug', $slug)
                ->firstOrFail();
        });
    }

    public static function clearCache(?Service $service = null, ?ServiceCategory $category = null): void
    {
        Cache::forget(self::FEATURED_CACHE_KEY);
        Cache::forget(self::CATEGORIES_CACHE_KEY);

        if ($service) {
            Cache::forget("service_detail_{$service->slug}");
        }

        if ($category) {
            Cache::forget("service_category_{$category->slug}");
        }
    }
}
