<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class ClientResource extends BaseResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'website_url' => $this->website_url,
            'logo' => new MediaResource($this->whenLoaded('logo')),
            'category' => $this->category,
            'featured' => $this->featured,
            'homepage_featured' => $this->homepage_featured,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
