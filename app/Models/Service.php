<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'description',
        'featured_image_id',
        'banner_image_id',
        'icon',
        'starting_price',
        'duration',
        'location',
        'display_order',
        'is_featured',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'starting_price' => 'decimal:2',
        'display_order' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function bannerImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner_image_id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(ServiceFeature::class)->ordered();
    }

    public function processes(): HasMany
    {
        return $this->hasMany(ServiceProcess::class)->ordered();
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ServiceFaq::class)->ordered();
    }

    public function activeFeatures(): HasMany
    {
        return $this->features()->active();
    }

    public function activeProcesses(): HasMany
    {
        return $this->processes()->active();
    }

    public function activeFaqs(): HasMany
    {
        return $this->faqs()->active();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('title');
    }

    public function getPriceLabelAttribute(): ?string
    {
        if ($this->starting_price === null) {
            return null;
        }

        return '$'.number_format((float) $this->starting_price, 2);
    }
}
