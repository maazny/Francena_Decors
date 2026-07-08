<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class HeroResource extends BaseResource
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
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'desktop_image' => new MediaResource($this->whenLoaded('desktopImage')),
            'mobile_image' => new MediaResource($this->whenLoaded('mobileImage')),
            'background_video' => new MediaResource($this->whenLoaded('backgroundVideo')),
            'overlay_color' => $this->overlay_color,
            'overlay_opacity' => $this->overlay_opacity,
            'overlay_rgba' => $this->overlay_rgba,
            'text_alignment' => $this->text_alignment,
            'content_position' => $this->content_position,
            'button_one_text' => $this->button_one_text,
            'button_one_url' => $this->button_one_url,
            'button_one_target' => $this->button_one_target,
            'button_two_text' => $this->button_two_text,
            'button_two_url' => $this->button_two_url,
            'button_two_target' => $this->button_two_target,
            'badge_text' => $this->badge_text,
            'badge_color' => $this->badge_color,
            'enable_animation' => $this->enable_animation,
            'animation_type' => $this->animation_type,
            'animation_duration' => $this->animation_duration,
            'display_order' => $this->display_order,
            'status' => $this->status,
        ];
    }
}
