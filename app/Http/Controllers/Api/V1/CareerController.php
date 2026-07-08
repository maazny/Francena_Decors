<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\JobOpening;
use App\Services\JobOpeningService;
use App\Http\Resources\Api\V1\CareerResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CareerController extends ApiController
{
    /**
     * @var JobOpeningService
     */
    protected $jobService;

    /**
     * CareerController constructor.
     */
    public function __construct(JobOpeningService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Display a listing of open job vacancies.
     */
    public function index(Request $request): JsonResponse
    {
        $query = JobOpening::with(['department', 'category', 'location'])->published();

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('short_description', 'like', $search)
                  ->orWhere('reference_no', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'department_id' => 'department_id',
            'category_id' => 'category_id',
            'location_id' => 'location_id',
            'employment_type' => 'employment_type',
            'experience_level' => 'experience_level',
            'featured' => 'featured',
        ]);

        $this->applySorting($query, ['title', 'application_deadline', 'published_at', 'id'], '-published_at');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, CareerResource::class, 'Job vacancies retrieved successfully');
    }

    /**
     * Display details of a single job opening.
     */
    public function show(string $slug): JsonResponse
    {
        $job = JobOpening::with(['department', 'category', 'location', 'skills', 'benefits', 'qualifications'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->success(new CareerResource($job), 'Job opening details retrieved successfully');
    }
}
