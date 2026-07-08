<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class MediaResource extends BaseResource
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
            'title' => $this->title,
            'alt_text' => $this->alt_text,
            'caption' => $this->caption,
            'description' => $this->description,
            'file_name' => $this->file_name,
            'original_name' => $this->original_name,
            'url' => $this->url,
            'thumbnail_url' => $this->thumbnail_url,
            'mime_type' => $this->mime_type,
            'extension' => $this->extension,
            'file_size' => $this->file_size,
            'human_size' => $this->human_size,
            'width' => $this->width,
            'height' => $this->height,
            'folder' => $this->folder,
            'is_image' => $this->is_image,
            'type' => $this->type,
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
