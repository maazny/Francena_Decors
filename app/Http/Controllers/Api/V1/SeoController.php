<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SeoPage;
use App\Http\Resources\Api\V1\SeoResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SeoController extends ApiController
{
    /**
     * Get SEO metadata metrics for a specific static page key or dynamic slug path.
     */
    public function show(string $page, Request $request): JsonResponse
    {
        $normalized = '/' . trim($page, '/');
        $cacheKey = 'api_seo_page_' . md5($normalized);
        $cacheTtl = config('api.cache_ttl', 3600);
        $cacheEnabled = config('api.cache_enabled', true);

        $fetchData = function () use ($normalized, $page) {
            return SeoPage::with('structuredData')
                ->where('page_key', $page)
                ->orWhere('slug', $normalized)
                ->firstOrFail();
        };

        $seoPage = $cacheEnabled
            ? Cache::remember($cacheKey, $cacheTtl, $fetchData)
            : $fetchData();

        return $this->success(new SeoResource($seoPage), 'SEO parameters retrieved successfully');
    }
}
