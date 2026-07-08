<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class ActivityLogResource extends BaseResource
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
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
            'module' => $this->module,
            'action' => $this->action?->value ?? $this->action,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'description' => $this->description,
            'old_values' => $this->old_values,
            'new_values' => $this->new_values,
            'ip_address' => $this->ip_address,
            'browser' => $this->browser,
            'device' => $this->device,
            'operating_system' => $this->operating_system,
            'user_agent' => $this->user_agent,
            'url' => $this->url,
            'method' => $this->method,
            'session_id' => $this->session_id,
            'request_id' => $this->request_id,
            'status' => $this->status?->value ?? $this->status,
            'user' => $this->when($this->relationLoaded('user') && $this->user, function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'role' => $this->when($this->relationLoaded('role') && $this->role, function() {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
