<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FooterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_column_id',
        'label',
        'url',
        'target',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function column(): BelongsTo
    {
        return $this->belongsTo(FooterColumn::class, 'footer_column_id');
    }
}
