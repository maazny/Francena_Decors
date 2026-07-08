<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class SeoResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'id' => $this->id,
            'page_type' => $this->page_type,
            'page_key' => $this->page_key,
            'slug' => $this->slug,
            'title' => $this->title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'canonical_url' => $this->canonical_url,
            'robots' => $this->robots,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'twitter_card' => $this->twitter_card,
            'custom_head_scripts' => $this->custom_head_scripts,
            'custom_footer_scripts' => $this->custom_footer_scripts,
            'structured_data' => $this->whenLoaded('structuredData'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
