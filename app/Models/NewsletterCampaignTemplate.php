<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsletterCampaignTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'html_content',
        'plain_content',
    ];

    /**
     * Get the campaigns that use this template.
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(NewsletterCampaign::class, 'template_id');
    }
}
