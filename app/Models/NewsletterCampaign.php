<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Enums\CampaignType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'subject',
        'preview_text',
        'campaign_type',
        'template_id',
        'html_content',
        'plain_text',
        'scheduled_at',
        'sent_at',
        'status',
        'sender_name',
        'sender_email',
        'analytics_utm_source',
        'analytics_utm_medium',
        'analytics_utm_campaign',
    ];

    protected $casts = [
        'campaign_type' => CampaignType::class,
        'status' => CampaignStatus::class,
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the template layout used by this campaign.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(NewsletterCampaignTemplate::class, 'template_id');
    }

    /**
     * Get the individual delivery track logs for this campaign.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(NewsletterCampaignLog::class, 'campaign_id');
    }

    /**
     * Scope a query to only include scheduled campaigns.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', CampaignStatus::SCHEDULED)
            ->whereNotNull('scheduled_at');
    }

    /**
     * Scope a query to only include draft campaigns.
     */
    public function scopeDrafts($query)
    {
        return $query->where('status', CampaignStatus::DRAFT);
    }
}
