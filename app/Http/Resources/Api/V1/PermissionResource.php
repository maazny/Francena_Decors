<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class PermissionResource extends BaseResource
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
            'guard_name' => $this->guard_name,
            'group_id' => $this->group_id,
            'group' => $this->when($this->relationLoaded('group') && $this->group, function () {
                return [
                    'id' => $this->group->id,
                    'name' => $this->group->name,
                    'slug' => $this->group->slug,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
