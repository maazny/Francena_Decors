<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_category_id',
        'name',
        'email',
        'phone',
        'company',
        'subject',
        'message',
        'attachment',
        'source',
        'status',
        'priority',
        'assigned_to',
        'follow_up_at',
        'ip_address',
        'user_agent',
        'is_read',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    /**
     * Get the media record uploaded as an attachment.
     */
    public function attachmentMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'attachment');
    }

    /**
     * Get the user who is assigned to this contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category that owns the contact.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ContactCategory::class, 'contact_category_id');
    }

    /**
     * Get the replies sent for the contact message.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ContactReply::class, 'contact_id');
    }

    /**
     * Get the internal notes for the contact.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(ContactNote::class, 'contact_id');
    }

    /**
     * Scope a query to only include unread/new messages.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope a query to order messages.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc');
    }
}
