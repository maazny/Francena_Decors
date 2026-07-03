<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'banner_image_id',
        'featured_image_id',
        'display_order',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'status' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => \App\Services\BlogService::clearCache());
        static::deleted(fn () => \App\Services\BlogService::clearCache());
        static::restored(fn () => \App\Services\BlogService::clearCache());
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    public function activePosts(): HasMany
    {
        return $this->posts()->active()->ordered();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('name');
    }
}
