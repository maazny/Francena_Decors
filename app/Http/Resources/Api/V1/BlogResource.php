<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class BlogResource extends BaseResource
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
            'author_id' => $this->author_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => new MediaResource($this->whenLoaded('featuredImage')),
            'banner_image' => new MediaResource($this->whenLoaded('bannerImage')),
            'display_order' => $this->display_order,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'reading_time' => $this->reading_time,
            'is_published' => $this->is_published,
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
            'author' => $this->when($this->relationLoaded('author') && $this->author, function() {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                    'email' => $this->author->email,
                ];
            }),
            'tags' => $this->when($this->relationLoaded('tags'), function() {
                return $this->tags->map(function($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ];
                });
            }),
            'galleries' => $this->whenLoaded('galleries'),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
