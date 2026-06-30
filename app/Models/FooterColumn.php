<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FooterColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_setting_id',
        'title',
        'type',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function footerSetting(): BelongsTo
    {
        return $this->belongsTo(FooterSetting::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(FooterLink::class)->orderBy('sort_order');
    }
}
