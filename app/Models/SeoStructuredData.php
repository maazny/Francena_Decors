<?php

namespace App\Models;

use App\Enums\StructuredDataType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoStructuredData extends Model
{
    use HasFactory;

    protected $table = 'seo_structured_data';

    protected $fillable = [
        'seo_page_id',
        'type',
        'schema_data',
        'custom_json_ld',
        'is_active',
    ];

    protected $casts = [
        'type' => StructuredDataType::class,
        'schema_data' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the page this schema is attached to.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(SeoPage::class, 'seo_page_id');
    }

    /**
     * Scope active schemas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
