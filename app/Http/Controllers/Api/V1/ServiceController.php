<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Service;
use App\Services\ServiceCatalogService;
use App\Http\Resources\Api\V1\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceController extends ApiController
{
    /**
     * @var ServiceCatalogService
     */
    protected $serviceCatalog;

    /**
     * ServiceController constructor.
     */
    public function __construct(ServiceCatalogService $serviceCatalog)
    {
        $this->serviceCatalog = $serviceCatalog;
    }

    /**
     * Display a listing of services with filters, sorting, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Service::with(['category', 'featuredImage']);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('short_description', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'category_id' => 'category_id',
            'is_featured' => 'is_featured',
            'status' => 'status',
        ]);

        $this->applySorting($query, ['title', 'starting_price', 'display_order', 'created_at'], 'display_order');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, ServiceResource::class, 'Services retrieved successfully');
    }

    /**
     * Get details of a single service by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $service = $this->serviceCatalog->serviceBySlug($slug);
        return $this->success(new ServiceResource($service), 'Service retrieved successfully');
    }
}
