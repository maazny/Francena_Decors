<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Project;
use App\Services\ProjectService;
use App\Http\Resources\Api\V1\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends ApiController
{
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * ProjectController constructor.
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of projects with custom service-layer filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'category' => $request->category_id,
            'location' => $request->location,
            'year' => $request->year,
            'budget' => $request->budget,
            'completion_status' => $request->completion_status,
            'featured' => $request->featured,
        ];

        $query = ProjectService::buildQuery($filters);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('description', 'like', $search)
                  ->orWhere('client_name', 'like', $search);
            });
        }

        $this->applySorting($query, ['title', 'budget', 'start_date', 'display_order', 'created_at'], 'display_order');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, ProjectResource::class, 'Projects retrieved successfully');
    }

    /**
     * Display details of a single project by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $project = Project::with([
            'category',
            'coverImage',
            'bannerImage',
            'clientLogo',
            'galleries.media',
            'beforeAfters.beforeMedia',
            'beforeAfters.afterMedia',
            'timelines',
            'materials',
            'technologies',
            'teamMembers.teamMember.profilePhoto',
            'relatedProjects.coverImage'
        ])->published()->where('slug', $slug)->firstOrFail();

        return $this->success(new ProjectResource($project), 'Project retrieved successfully');
    }
}
