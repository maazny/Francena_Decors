<?php

namespace App\Models;

use App\Enums\SeoPageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_type',
        'page_key',
        'slug',
        'title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'robots',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'custom_head_scripts',
        'custom_footer_scripts',
        'seo_pageable_type',
        'seo_pageable_id',
    ];

    protected $casts = [
        'page_type' => SeoPageType::class,
    ];

    /**
     * Get the owning morphic model.
     */
    public function seo_pageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get structured JSON-LD schemas mapped to this page.
     */
    public function structuredData(): HasMany
    {
        return $this->hasMany(SeoStructuredData::class, 'seo_page_id');
    }

    /**
     * Scope static pages.
     */
    public function scopeStaticPages($query)
    {
        return $query->where('page_type', SeoPageType::STATIC);
    }

    /**
     * Scope dynamic pages.
     */
    public function scopeDynamicPages($query)
    {
        return $query->where('page_type', SeoPageType::DYNAMIC);
    }

    /**
     * Scope module pages.
     */
    public function scopeModulePages($query)
    {
        return $query->where('page_type', SeoPageType::MODULE);
    }

    /**
     * Scope find by unique key.
     */
    public function scopeFindByKey($query, string $key)
    {
        return $query->where('page_key', $key);
    }

    /**
     * Scope find by slug path.
     */
    public function scopeFindBySlug($query, string $slug)
    {
        $normalized = '/' . trim($slug, '/');
        return $query->where('slug', $normalized);
    }
}
