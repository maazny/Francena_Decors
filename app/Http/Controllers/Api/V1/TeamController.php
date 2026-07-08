<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TeamMember;
use App\Services\TeamService;
use App\Http\Resources\Api\V1\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeamController extends ApiController
{
    /**
     * @var TeamService
     */
    protected $teamService;

    /**
     * TeamController constructor.
     */
    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Display a listing of team members using service query filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'department' => $request->department_id,
            'designation' => $request->designation,
            'experience_min' => $request->experience_min,
            'featured' => $request->featured,
            'status' => $request->input('status', true),
        ];

        if (!$request->user('sanctum')) {
            $filters['status'] = true;
        }

        $query = TeamService::buildQuery($filters);

        $this->applySorting($query, ['full_name', 'experience_years', 'display_order', 'created_at'], 'display_order');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, TeamResource::class, 'Team members retrieved successfully');
    }
}
