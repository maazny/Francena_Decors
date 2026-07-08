<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class DashboardResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
