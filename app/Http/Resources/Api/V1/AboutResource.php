<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class AboutResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            return [
                'section' => new self($this->resource['section'] ?? null),
                'values' => $this->resource['values'] ?? [],
                'timelines' => $this->resource['timelines'] ?? [],
                'why_choose_us' => $this->resource['whyChooseUs'] ?? [],
            ];
        }

        if (!$this->resource) {
            return [];
        }

        return [
            'id' => $this->id,
            'company_story' => $this->company_story,
            'mission' => $this->mission,
            'vision' => $this->vision,
            'chairman_message' => $this->chairman_message,
            'chairman_name' => $this->chairman_name,
            'chairman_designation' => $this->chairman_designation,
            'chairman_image' => new MediaResource($this->whenLoaded('chairmanImage')),
            'company_video' => new MediaResource($this->whenLoaded('companyVideo')),
            'brochure_file' => new MediaResource($this->whenLoaded('brochureFile')),
            'experience_years' => $this->experience_years,
            'completed_projects' => $this->completed_projects,
            'happy_clients' => $this->happy_clients,
            'team_members' => $this->team_members,
            'status' => $this->status,
        ];
    }
}
