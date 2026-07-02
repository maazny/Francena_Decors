<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_post_id',
        'media_id',
        'caption',
        'display_order',
        'status',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'status' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
