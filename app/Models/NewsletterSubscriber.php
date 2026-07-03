<?php

namespace App\Models;

use App\Enums\SubscriberSource;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'verification_status',
        'status',
        'source',
        'ip_address',
        'user_agent',
        'preferred_language',
        'tags',
        'verification_token',
        'unsubscribe_token',
        'verified_at',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'verification_status' => 'boolean',
        'status' => SubscriptionStatus::class,
        'source' => SubscriberSource::class,
        'tags' => 'array',
        'verified_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    /**
     * Get the groups this subscriber belongs to.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            NewsletterGroup::class,
            'newsletter_group_subscriber',
            'subscriber_id',
            'group_id'
        )->withTimestamps();
    }

    /**
     * Get the logs track record for this subscriber.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(NewsletterCampaignLog::class, 'subscriber_id');
    }

    /**
     * Scope a query to only include active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', SubscriptionStatus::ACTIVE);
    }

    /**
     * Scope a query to search subscribers.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
