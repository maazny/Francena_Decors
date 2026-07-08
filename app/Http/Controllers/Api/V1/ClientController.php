<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ClientBrand;
use App\Services\ClientBrandService;
use App\Http\Resources\Api\V1\ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientController extends ApiController
{
    /**
     * @var ClientBrandService
     */
    protected $clientBrandService;

    /**
     * ClientController constructor.
     */
    public function __construct(ClientBrandService $clientBrandService)
    {
        $this->clientBrandService = $clientBrandService;
    }

    /**
     * Display a listing of client brands.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'category' => $request->category,
            'featured' => $request->featured,
        ];

        $query = ClientBrandService::buildQuery($filters);

        $this->applySorting($query, ['name', 'display_order', 'created_at'], 'display_order');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, ClientResource::class, 'Client brands retrieved successfully');
    }
}
