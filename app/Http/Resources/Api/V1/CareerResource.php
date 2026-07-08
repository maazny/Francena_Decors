<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class CareerResource extends BaseResource
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
            'category_id' => $this->category_id,
            'location_id' => $this->location_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'reference_no' => $this->reference_no,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'employment_type' => $this->employment_type,
            'experience_level' => $this->experience_level,
            'vacancies' => $this->vacancies,
            'salary_from' => $this->salary_from,
            'salary_to' => $this->salary_to,
            'salary_type' => $this->salary_type,
            'application_deadline' => $this->application_deadline?->toIso8601String(),
            'featured' => $this->featured,
            'homepage_featured' => $this->homepage_featured,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'seo_keywords' => $this->seo_keywords,
            'department' => $this->when($this->relationLoaded('department') && $this->department, function() {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                ];
            }),
            'category' => $this->when($this->relationLoaded('category') && $this->category, function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'location' => $this->when($this->relationLoaded('location') && $this->location, function() {
                return [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                ];
            }),
            'skills' => $this->whenLoaded('skills'),
            'benefits' => $this->whenLoaded('benefits'),
            'qualifications' => $this->whenLoaded('qualifications'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
