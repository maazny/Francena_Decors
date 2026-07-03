<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
        'message',
        'attachment',
    ];

    /**
     * Get the contact that owns the reply.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Get the user who sent the reply.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the media record uploaded as an attachment.
     */
    public function attachmentMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'attachment');
    }
}
