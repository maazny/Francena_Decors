<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'status' => $this->status ?? true,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('directPermissions')),
            'all_permissions' => $this->when($this->relationLoaded('roles') || $this->relationLoaded('directPermissions'), function () {
                return array_values(array_unique(array_merge(
                    $this->directPermissions()->pluck('name')->toArray(),
                    \App\Models\Permission::whereHas('roles', function ($query) {
                        $query->whereIn('roles.id', $this->roles()->pluck('roles.id'));
                    })->pluck('name')->toArray()
                )));
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
