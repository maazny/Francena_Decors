<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\HeroSlider;
use App\Models\SiteSetting;
use App\Models\ThemeSetting;
use App\Models\FooterSetting;
use App\Models\Project;
use App\Models\Testimonial;
use App\Http\Resources\Api\V1\HeroResource;
use App\Http\Resources\Api\V1\ProjectResource;
use App\Http\Resources\Api\V1\TestimonialResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HomeController extends ApiController
{
    /**
     * Get aggregate landing homepage data including hero sliders, featured items, and settings.
     */
    public function index(): JsonResponse
    {
        $cacheKey = 'api_v1_home';
        $cacheTtl = config('api.cache_ttl', 3600);
        $cacheEnabled = config('api.cache_enabled', true);

        $fetchData = function () {
            return [
                'hero_sliders' => HeroResource::collection(
                    HeroSlider::with(['desktopImage', 'mobileImage', 'backgroundVideo'])
                        ->active()
                        ->published()
                        ->ordered()
                        ->get()
                ),
                'featured_projects' => ProjectResource::collection(
                    Project::with(['coverImage', 'category'])
                        ->published()
                        ->homepageFeatured()
                        ->ordered()
                        ->limit(6)
                        ->get()
                ),
                'featured_testimonials' => TestimonialResource::collection(
                    Testimonial::with(['clientPhoto', 'clientLogo', 'category'])
                        ->homepageFeatured()
                        ->ordered()
                        ->limit(5)
                        ->get()
                ),
                'settings' => [
                    'site' => SiteSetting::first(),
                    'theme' => ThemeSetting::getCached(),
                    'footer' => FooterSetting::first(),
                ],
            ];
        };

        $data = $cacheEnabled
            ? Cache::remember($cacheKey, $cacheTtl, $fetchData)
            : $fetchData();

        return $this->success($data, 'Homepage data retrieved successfully');
    }

    /**
     * Get home page hero slider animations.
     */
    public function hero(): JsonResponse
    {
        $cacheKey = 'api_v1_hero';
        $cacheTtl = config('api.cache_ttl', 3600);
        $cacheEnabled = config('api.cache_enabled', true);

        $fetchData = function () {
            return HeroResource::collection(
                HeroSlider::with(['desktopImage', 'mobileImage', 'backgroundVideo'])
                    ->active()
                    ->published()
                    ->ordered()
                    ->get()
            );
        };

        $data = $cacheEnabled
            ? Cache::remember($cacheKey, $cacheTtl, $fetchData)
            : $fetchData();

        return $this->success($data, 'Hero sliders retrieved successfully');
    }
}
