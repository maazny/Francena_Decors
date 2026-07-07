<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSitemap extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'loc',
        'lastmod',
        'changefreq',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'lastmod' => 'datetime',
        'priority' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Scope active sitemaps.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
