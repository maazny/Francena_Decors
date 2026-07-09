<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'category' => $request->query('category'),
            'location' => $request->query('location'),
            'year' => $request->query('year'),
            'budget' => $request->query('budget'),
            'completion_status' => $request->query('completion_status'),
            'featured' => $request->query('featured'),
        ];

        $projects = ProjectService::buildQuery($filters)
            ->paginate(9)
            ->withQueryString();

        $categories = Cache::rememberForever('project_categories', fn () => ProjectCategory::active()->ordered()->get());
        $years = Project::query()->published()->selectRaw('EXTRACT(YEAR FROM start_date) as year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');
        $featuredProject = Project::query()->with(['category', 'coverImage'])->published()->featured()->ordered()->first();

        return view('projects.index', compact('projects', 'categories', 'years', 'filters', 'featuredProject'));
    }

    public function show(Project $project): View
    {
        abort_unless($project->status === 'published', 404);

        $project->load(['category', 'coverImage', 'bannerImage', 'clientLogo', 'galleries.media', 'beforeAfters.beforeMedia', 'beforeAfters.afterMedia', 'timelines.media', 'materials', 'technologies', 'teamMembers.teamMember']);

        $relatedProjects = $project->relatedProjects()->with(['category', 'coverImage'])->published()->ordered()->take(3)->get();

        return view('projects.show', compact('project', 'relatedProjects'));
    }

    public function category(ProjectCategory $projectCategory): View
    {
        abort_unless($projectCategory->status, 404);

        $projects = ProjectService::buildQuery(['category' => $projectCategory->id])
            ->paginate(9)
            ->withQueryString();

        return view('projects.category', compact('projectCategory', 'projects'));
    }
}
