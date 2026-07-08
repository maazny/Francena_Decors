<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Resolve the resource to an array, applying sparse fieldsets.
     *
     * @param  Request|null  $request
     * @return array
     */
    public function resolve($request = null): array
    {
        $data = parent::resolve($request);
        $request = $request ?: request();

        $fields = $request->input('fields');
        if (empty($fields)) {
            return $data;
        }

        if (is_array($fields)) {
            $resourceType = strtolower(class_basename($this->resource));
            $tableName = $this->resource instanceof \Illuminate\Database\Eloquent\Model ? $this->resource->getTable() : null;

            $fieldsList = $fields[$resourceType] ?? $fields[$tableName] ?? null;
            if ($fieldsList) {
                $allowed = array_map('trim', explode(',', $fieldsList));
                return array_intersect_key($data, array_flip($allowed));
            }
        } elseif (is_string($fields)) {
            $allowed = array_map('trim', explode(',', $fields));
            return array_intersect_key($data, array_flip($allowed));
        }

        return $data;
    }
}
