<?php

namespace App\Services;

use App\Models\ClientBrand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ClientBrandService
{
    public static function clearCache(?ClientBrand $brand = null): void
    {
        Cache::forget('homepage_client_brands');
        Cache::forget('featured_client_brands');
        Cache::forget('client_brand_categories');

        if ($brand) {
            Cache::forget('client_brand_'.$brand->id);
        }
    }

    public static function makeSlug(string $name, ?string $slug = null): string
    {
        return filled($slug) ? Str::slug($slug) : Str::slug($name);
    }

    public static function buildQuery(array $filters = [])
    {
        $query = ClientBrand::query()->with('logo')->active()->ordered();

        if (! empty($filters['search'])) {
            $search = trim((string) $filters['search']);
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (! empty($filters['featured'])) {
            $query->where('featured', true);
        }

        return $query;
    }

    public static function getHomepageClientBrands(int $limit = 6)
    {
        return Cache::remember('homepage_client_brands', 3600, function () use ($limit) {
            return ClientBrand::query()->with('logo')->homepageFeatured()->ordered()->limit($limit)->get();
        });
    }

    public static function getFeaturedClientBrands(int $limit = 8)
    {
        return Cache::remember('featured_client_brands', 3600, function () use ($limit) {
            return ClientBrand::query()->with('logo')->featured()->ordered()->limit($limit)->get();
        });
    }

    public static function getCategories(): array
    {
        return Cache::remember('client_brand_categories', 3600, function () {
            return ClientBrand::query()->whereNotNull('category')->distinct('category')->pluck('category')->filter()->values()->all();
        });
    }
}
