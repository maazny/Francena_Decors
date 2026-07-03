<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_dynamic',
        'filters',
        'status',
        'display_order',
    ];

    protected $casts = [
        'is_dynamic' => 'boolean',
        'status' => 'boolean',
        'filters' => 'array',
        'display_order' => 'integer',
    ];

    /**
     * Get the subscribers inside this segment group.
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(
            NewsletterSubscriber::class,
            'newsletter_group_subscriber',
            'group_id',
            'subscriber_id'
        )->withTimestamps();
    }

    /**
     * Scope a query to only include active groups.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to order groups.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc');
    }
}
