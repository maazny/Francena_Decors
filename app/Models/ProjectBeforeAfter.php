<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBeforeAfter extends Model
{
    use HasFactory;

    protected $table = 'project_before_after';

    protected $fillable = [
        'project_id',
        'before_media_id',
        'after_media_id',
        'title',
        'description',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function beforeMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'before_media_id');
    }

    public function afterMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'after_media_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('id');
    }
}
