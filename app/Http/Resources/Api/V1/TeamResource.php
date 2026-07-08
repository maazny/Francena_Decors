<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class TeamResource extends BaseResource
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
            'department_id' => $this->department_id,
            'full_name' => $this->full_name,
            'slug' => $this->slug,
            'designation' => $this->designation,
            'short_bio' => $this->short_bio,
            'full_bio' => $this->full_bio,
            'profile_photo' => new MediaResource($this->whenLoaded('profilePhoto')),
            'cover_photo' => new MediaResource($this->whenLoaded('coverPhoto')),
            'email' => $this->email,
            'phone' => $this->phone,
            'experience_years' => $this->experience_years,
            'qualification' => $this->qualification,
            'specialization' => $this->specialization,
            'display_order' => $this->display_order,
            'featured' => $this->featured,
            'homepage_featured' => $this->homepage_featured,
            'joining_date' => $this->joining_date?->toDateString(),
            'status' => $this->status,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
            'department' => $this->when($this->relationLoaded('department') && $this->department, function() {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                ];
            }),
            'social_links' => $this->whenLoaded('socialLinks'),
            'skills' => $this->whenLoaded('skills'),
            'certifications' => $this->whenLoaded('certifications'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
