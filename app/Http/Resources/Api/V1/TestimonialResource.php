<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class TestimonialResource extends BaseResource
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
            'testimonial_category_id' => $this->testimonial_category_id,
            'client_name' => $this->client_name,
            'client_company' => $this->client_company,
            'client_designation' => $this->client_designation,
            'client_photo' => new MediaResource($this->whenLoaded('clientPhoto')),
            'client_logo' => new MediaResource($this->whenLoaded('clientLogo')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'rating' => $this->rating,
            'star_rating' => $this->star_rating,
            'title' => $this->title,
            'testimonial' => $this->testimonial,
            'video_url' => $this->video_url,
            'youtube_url' => $this->youtube_url,
            'location' => $this->location,
            'featured' => $this->featured,
            'homepage_featured' => $this->homepage_featured,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'approved_at' => $this->approved_at?->toIso8601String(),
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
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
