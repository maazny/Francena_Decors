<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'redirect_id',
        'url',
        'referrer',
        'user_agent',
        'ip_address',
        'crawler_detected',
    ];

    protected $casts = [
        'crawler_detected' => 'boolean',
    ];

    /**
     * Get the redirect that triggered this log.
     */
    public function redirect(): BelongsTo
    {
        return $this->belongsTo(SeoRedirect::class, 'redirect_id');
    }

    /**
     * Scope only crawler request hits.
     */
    public function scopeCrawlers($query)
    {
        return $query->where('crawler_detected', true);
    }

    /**
     * Scope only normal user/browser hits.
     */
    public function scopeNonCrawlers($query)
    {
        return $query->where('crawler_detected', false);
    }
}
