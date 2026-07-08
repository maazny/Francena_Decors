<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\AboutSection;
use App\Http\Resources\Api\V1\AboutResource;
use Illuminate\Http\JsonResponse;

class AboutController extends ApiController
{
    /**
     * Get company story, timeline, core values, and corporate information.
     */
    public function index(): JsonResponse
    {
        $data = AboutSection::getCached();
        return $this->success(new AboutResource($data), 'About page details retrieved successfully');
    }
}
