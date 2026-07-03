<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterCampaignLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'delivery_status',
        'error_message',
        'opened',
        'clicked',
        'bounced',
        'failed',
        'sent_at',
        'opened_at',
        'clicked_at',
    ];

    protected $casts = [
        'opened' => 'boolean',
        'clicked' => 'boolean',
        'bounced' => 'boolean',
        'failed' => 'boolean',
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    /**
     * Get the campaign associated with this log.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(NewsletterCampaign::class, 'campaign_id');
    }

    /**
     * Get the subscriber associated with this log.
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(NewsletterSubscriber::class, 'subscriber_id');
    }

    /**
     * Scope a query to only include logs that were opened.
     */
    public function scopeOpened($query)
    {
        return $query->where('opened', true);
    }

    /**
     * Scope a query to only include logs that were clicked.
     */
    public function scopeClicked($query)
    {
        return $query->where('clicked', true);
    }

    /**
     * Scope a query to only include bounced logs.
     */
    public function scopeBounced($query)
    {
        return $query->where('bounced', true);
    }
}
