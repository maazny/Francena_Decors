<?php

namespace App\Services;

use App\Models\Testimonial;
use App\Models\TestimonialCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TestimonialService
{
    public static function clearCache(?Testimonial $testimonial = null, ?TestimonialCategory $category = null): void
    {
        Cache::forget('homepage_testimonials');
        Cache::forget('featured_testimonials');
        Cache::forget('testimonial_categories');

        if ($testimonial) {
            Cache::forget('testimonial_'.$testimonial->id);
        }

        if ($category) {
            Cache::forget('testimonial_category_'.$category->id);
        }
    }

    public static function makeSlug(string $clientName, ?string $slug = null): string
    {
        return filled($slug) ? Str::slug($slug) : Str::slug($clientName);
    }

    public static function buildQuery(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Testimonial::query()
            ->with(['category', 'clientPhoto', 'clientLogo', 'project'])
            ->published()
            ->ordered();

        if (! empty($filters['category'])) {
            $query->where('testimonial_category_id', $filters['category']);
        }

        if (! empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (! empty($filters['location'])) {
            $query->where('location', 'like', '%'.$filters['location'].'%');
        }

        if (! empty($filters['company'])) {
            $query->where('client_company', 'like', '%'.$filters['company'].'%');
        }

        if (! empty($filters['featured'])) {
            $query->where('featured', true);
        }

        if (! empty($filters['has_video'])) {
            $query->withVideo();
        }

        return $query;
    }

    public static function getHomepageTestimonials(int $limit = 3): mixed
    {
        return Cache::remember('homepage_testimonials', 3600, function () use ($limit) {
            return Testimonial::query()
                ->with(['category', 'clientPhoto', 'clientLogo', 'project'])
                ->homepageFeatured()
                ->ordered()
                ->limit($limit)
                ->get();
        });
    }

    public static function getFeaturedTestimonials(int $limit = 6): mixed
    {
        return Cache::remember('featured_testimonials', 3600, function () use ($limit) {
            return Testimonial::query()
                ->with(['category', 'clientPhoto', 'clientLogo', 'project'])
                ->featured()
                ->ordered()
                ->limit($limit)
                ->get();
        });
    }

    public static function getCategories(): mixed
    {
        return Cache::remember('testimonial_categories', 3600, function () {
            return TestimonialCategory::active()->ordered()->get();
        });
    }
}
