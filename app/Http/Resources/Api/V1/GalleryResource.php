<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class GalleryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        if ($this->resource instanceof \App\Models\Media) {
            return [
                'id' => $this->id,
                'title' => $this->title,
                'alt_text' => $this->alt_text,
                'caption' => $this->caption,
                'url' => $this->url,
                'thumbnail_url' => $this->thumbnail_url,
                'is_image' => $this->is_image,
                'created_at' => $this->created_at?->toIso8601String(),
            ];
        }

        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'display_order' => $this->display_order,
            'media' => new MediaResource($this->whenLoaded('media')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
