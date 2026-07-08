<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class ServiceResource extends BaseResource
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
            'category_id' => $this->category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'featured_image' => new MediaResource($this->whenLoaded('featuredImage')),
            'banner_image' => new MediaResource($this->whenLoaded('bannerImage')),
            'icon' => $this->icon,
            'starting_price' => $this->starting_price,
            'price_label' => $this->price_label,
            'duration' => $this->duration,
            'location' => $this->location,
            'display_order' => $this->display_order,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
            'category' => $this->when($this->relationLoaded('category') && $this->category, function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'features' => $this->whenLoaded('features'),
            'processes' => $this->whenLoaded('processes'),
            'faqs' => $this->whenLoaded('faqs'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
