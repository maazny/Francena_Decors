<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ServiceFaq;
use App\Http\Resources\Api\V1\FaqResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FaqController extends ApiController
{
    /**
     * Display a listing of FAQs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ServiceFaq::with('service')->active()->ordered();

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', $search)
                  ->orWhere('answer', 'like', $search);
            });
        }

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, FaqResource::class, 'FAQs retrieved successfully');
    }
}
