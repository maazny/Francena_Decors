<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class BackupResource extends BaseResource
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
            'backup_name' => $this->backup_name,
            'backup_type' => $this->backup_type?->value ?? $this->backup_type,
            'description' => $this->description,
            'storage_disk' => $this->storage_disk,
            'storage_path' => $this->storage_path,
            'file_name' => $this->file_name,
            'file_extension' => $this->file_extension,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'checksum' => $this->checksum,
            'compression' => $this->compression,
            'encryption' => $this->encryption,
            'status' => $this->status?->value ?? $this->status,
            'started_at' => $this->started_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'duration_seconds' => $this->duration_seconds,
            'created_by' => $this->created_by,
            'restored_by' => $this->restored_by,
            'restore_point' => $this->restore_point,
            'is_verified' => $this->is_verified,
            'notes' => $this->notes,
            'last_downloaded_at' => $this->last_downloaded_at?->toIso8601String(),
            'download_count' => $this->download_count,
            'failure_reason' => $this->failure_reason,
            'creator' => $this->when($this->relationLoaded('creator') && $this->creator, function() {
                return [
                    'id' => $this->creator->id,
                    'name' => $this->creator->name,
                ];
            }),
            'restorer' => $this->when($this->relationLoaded('restorer') && $this->restorer, function() {
                return [
                    'id' => $this->restorer->id,
                    'name' => $this->restorer->name,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
