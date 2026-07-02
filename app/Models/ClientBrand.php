<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientBrand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'website_url',
        'logo_id',
        'category',
        'featured',
        'homepage_featured',
        'display_order',
        'status',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'homepage_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->published();
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
        return $query->orderBy('display_order')->orderBy('name');
    }
}
