<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class ProjectResource extends BaseResource
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
            'project_category_id' => $this->project_category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'client_name' => $this->client_name,
            'client_company' => $this->client_company,
            'client_logo' => new MediaResource($this->whenLoaded('clientLogo')),
            'budget' => $this->budget,
            'currency' => $this->currency,
            'project_manager' => $this->project_manager,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'completion_percentage' => $this->completion_percentage,
            'is_completed' => $this->is_completed,
            'status' => $this->status,
            'featured' => $this->featured,
            'homepage_featured' => $this->homepage_featured,
            'location' => $this->location,
            'google_map_embed' => $this->google_map_embed,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'cover_image' => new MediaResource($this->whenLoaded('coverImage')),
            'banner_image' => new MediaResource($this->whenLoaded('bannerImage')),
            'video_url' => $this->video_url,
            'youtube_url' => $this->youtube_url,
            'project_duration' => $this->project_duration,
            'project_area' => $this->project_area,
            'display_order' => $this->display_order,
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
            'galleries' => $this->whenLoaded('galleries'),
            'before_afters' => $this->whenLoaded('beforeAfters'),
            'timelines' => $this->whenLoaded('timelines'),
            'materials' => $this->whenLoaded('materials'),
            'technologies' => $this->whenLoaded('technologies'),
            'team_members' => $this->whenLoaded('teamMembers'),
            'related_projects' => self::collection($this->whenLoaded('relatedProjects')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
