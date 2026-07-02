<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_id',
        'banner_image_id',
        'display_order',
        'is_featured',
        'status',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function bannerImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner_image_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(BlogGallery::class, 'blog_post_id')->ordered();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', Carbon::now());
            });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('title');
    }

    public function getIsPublishedAttribute(): bool
    {
        if (! $this->status) {
            return false;
        }

        return $this->published_at === null || $this->published_at->lte(Carbon::now());
    }

    public function getExcerptAttribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        return str($this->content)->limit(200)->toString();
    }

    public function getReadingTimeAttribute(): int
    {
        if ($this->reading_time !== null) {
            return (int) $this->reading_time;
        }

        return (new \App\Services\BlogService())->estimateReadingTime($this->content ?? '');
    }
}
