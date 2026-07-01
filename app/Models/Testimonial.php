<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'testimonial_category_id',
        'client_name',
        'client_company',
        'client_designation',
        'client_photo_id',
        'client_logo_id',
        'project_id',
        'rating',
        'title',
        'testimonial',
        'video_url',
        'youtube_url',
        'location',
        'featured',
        'homepage_featured',
        'display_order',
        'status',
        'approved_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'rating' => 'integer',
        'featured' => 'boolean',
        'homepage_featured' => 'boolean',
        'display_order' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(TestimonialCategory::class, 'testimonial_category_id');
    }

    public function clientPhoto(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'client_photo_id');
    }

    public function clientLogo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'client_logo_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getStarRatingAttribute(): string
    {
        $stars = '';
        for ($i = 0; $i < $this->rating; $i++) {
            $stars .= '★';
        }
        for ($i = $this->rating; $i < 5; $i++) {
            $stars .= '☆';
        }
        return $stars;
    }

    public function hasVideo(): bool
    {
        return filled($this->video_url) || filled($this->youtube_url);
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('approved_at', '!=', null);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true)->published();
    }

    public function scopeHomepageFeatured(Builder $query): Builder
    {
        return $query->where('homepage_featured', true)->published();
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc')->orderBy('id', 'desc');
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('testimonial_category_id', $categoryId);
    }

    public function scopeByRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    public function scopeWithVideo(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNotNull('video_url')->orWhereNotNull('youtube_url');
        });
    }
}
