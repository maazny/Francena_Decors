<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\SiteSetting;
use App\Models\ThemeSetting;
use App\Models\FooterSetting;
use App\Http\Resources\Api\V1\SettingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SettingController extends ApiController
{
    /**
     * Get aggregate configuration settings including active Theme values, Site name properties, and footer content.
     */
    public function index(): JsonResponse
    {
        $cacheKey = 'api_v1_settings';
        $cacheTtl = config('api.cache_ttl', 3600);
        $cacheEnabled = config('api.cache_enabled', true);

        $fetchData = function () {
            return [
                'site' => SiteSetting::first(),
                'theme' => ThemeSetting::getCached(),
                'footer' => FooterSetting::first(),
            ];
        };

        $settings = $cacheEnabled
            ? Cache::remember($cacheKey, $cacheTtl, $fetchData)
            : $fetchData();

        return $this->success(new SettingResource($settings), 'Settings retrieved successfully');
    }
}
