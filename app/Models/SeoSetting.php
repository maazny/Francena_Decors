<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'robots',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'favicon',
        'apple_touch_icon',
        'manifest_file',
        'theme_color',
        'robots_txt_rules',
    ];

    /**
     * Return the cached global settings instance.
     */
    public static function getCached(): self
    {
        return Cache::rememberForever('seo_settings', function () {
            return self::firstOrCreate([]);
        });
    }

    /**
     * Clear the global settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('seo_settings');
    }
}
