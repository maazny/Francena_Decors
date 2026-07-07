<?php

namespace App\Models;

use App\Enums\RedirectType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeoRedirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'target_url',
        'type',
        'is_wildcard',
        'is_active',
        'hit_count',
    ];

    protected $casts = [
        'type' => RedirectType::class,
        'is_wildcard' => 'boolean',
        'is_active' => 'boolean',
        'hit_count' => 'integer',
    ];

    /**
     * Get traffic logs triggered by this redirect.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(SeoLog::class, 'redirect_id');
    }

    /**
     * Scope active redirects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope wildcard redirects.
     */
    public function scopeWildcards($query)
    {
        return $query->where('is_wildcard', true);
    }
}
