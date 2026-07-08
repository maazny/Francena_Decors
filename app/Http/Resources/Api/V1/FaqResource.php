<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class FaqResource extends BaseResource
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
            'service_id' => $this->service_id,
            'question' => $this->question,
            'answer' => $this->answer,
            'display_order' => $this->display_order,
            'status' => $this->status,
            'service' => $this->when($this->relationLoaded('service') && $this->service, function() {
                return [
                    'id' => $this->service->id,
                    'title' => $this->service->title,
                    'slug' => $this->service->slug,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
