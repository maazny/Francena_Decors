<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Testimonial;
use App\Services\TestimonialService;
use App\Http\Resources\Api\V1\TestimonialResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestimonialController extends ApiController
{
    /**
     * @var TestimonialService
     */
    protected $testimonialService;

    /**
     * TestimonialController constructor.
     */
    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Display a listing of client reviews using service queries.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'category' => $request->category_id,
            'rating' => $request->rating,
            'location' => $request->location,
            'company' => $request->company,
            'featured' => $request->featured,
            'has_video' => $request->has_video,
        ];

        $query = TestimonialService::buildQuery($filters);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', $search)
                  ->orWhere('title', 'like', $search)
                  ->orWhere('testimonial', 'like', $search);
            });
        }

        $this->applySorting($query, ['client_name', 'rating', 'display_order', 'created_at'], 'display_order');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, TestimonialResource::class, 'Testimonials retrieved successfully');
    }
}
