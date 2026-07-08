<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class SettingResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            return [
                'site' => $this->resource['site'] ?? null,
                'theme' => $this->resource['theme'] ?? null,
                'footer' => $this->resource['footer'] ?? null,
            ];
        }

        return parent::toArray($request);
    }
}
