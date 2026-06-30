<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FooterWidget extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_setting_id',
        'title',
        'content',
        'icon',
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
}
