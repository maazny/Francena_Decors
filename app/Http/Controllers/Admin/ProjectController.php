<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Media;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectGallery;
use App\Models\ProjectBeforeAfter;
use App\Models\ProjectMaterial;
use App\Models\ProjectTechnology;
use App\Models\ProjectTimeline;
use App\Models\ProjectTeamMember;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $projects = Project::with(['category', 'coverImage'])
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($categoryId, fn ($query) => $query->where('project_category_id', $categoryId))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'search' => $search,
            'status' => $status,
            'categoryId' => $categoryId,
            'categories' => ProjectCategory::active()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project([
                'display_order' => Project::withTrashed()->max('display_order') + 1,
                'status' => 'draft',
                'completion_percentage' => 0,
                'featured' => false,
                'homepage_featured' => false,
            ]),
            'categories' => ProjectCategory::active()->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
            'teamMembers' => User::orderBy('name')->get(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $project = Project::create($this->payload($request));
            $this->syncRelatedData($project, $request);
        });

        ProjectService::clearCache();

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project->load(['category', 'coverImage', 'bannerImage', 'clientLogo', 'galleries.media', 'beforeAfters.beforeMedia', 'beforeAfters.afterMedia', 'timelines.media', 'materials', 'technologies', 'teamMembers.teamMember']),
            'categories' => ProjectCategory::active()->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
            'teamMembers' => User::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project): void {
            $project->update($this->payload($request));
            $this->syncRelatedData($project, $request);
        });

        ProjectService::clearCache(project: $project, category: $project->category);

        return redirect()->route('admin.projects.edit', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        ProjectService::clearCache(project: $project, category: $project->category);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function restore(int $project): RedirectResponse
    {
        $item = Project::withTrashed()->findOrFail($project);
        $item->restore();
        ProjectService::clearCache(project: $item, category: $item->category);

        return redirect()->route('admin.projects.index')->with('success', 'Project restored successfully.');
    }

    public function toggleStatus(Project $project): RedirectResponse
    {
        $project->update(['status' => $project->status === 'published' ? 'draft' : 'published']);
        ProjectService::clearCache(project: $project, category: $project->category);

        return redirect()->route('admin.projects.index')->with('success', 'Project status updated successfully.');
    }

    public function duplicate(Project $project): RedirectResponse
    {
        DB::transaction(function () use ($project): void {
            $copy = $project->replicate();
            $copy->title = $project->title.' Copy';
            $copy->slug = $project->slug.'-copy-'.now()->timestamp;
            $copy->status = 'draft';
            $copy->featured = false;
            $copy->homepage_featured = false;
            $copy->display_order = Project::withTrashed()->max('display_order') + 1;
            $copy->save();
        });

        ProjectService::clearCache();

        return redirect()->route('admin.projects.index')->with('success', 'Project duplicated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = Project::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => 'published']),
                'deactivate' => $query->update(['status' => 'draft']),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        ProjectService::clearCache();

        return redirect()->route('admin.projects.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreProjectRequest $request): array
    {
        $data = $request->validated();
        $data['slug'] = ProjectService::makeSlug($data['title'], $data['slug'] ?? null);
        $data['featured'] = $request->boolean('featured');
        $data['homepage_featured'] = $request->boolean('homepage_featured');
        $data['completion_percentage'] = (int) ($data['completion_percentage'] ?? 0);

        return $data;
    }

    private function syncRelatedData(Project $project, Request $request): void
    {
        $project->galleries()->delete();
        $project->beforeAfters()->delete();
        $project->timelines()->delete();
        $project->materials()->delete();
        $project->technologies()->delete();
        $project->teamMembers()->delete();

        $galleryIds = (array) $request->input('gallery_media_ids', []);
        foreach ($galleryIds as $index => $mediaId) {
            ProjectGallery::create([
                'project_id' => $project->id,
                'media_id' => $mediaId,
                'caption' => $request->input('gallery_captions.'.$index),
                'display_order' => $index + 1,
            ]);
        }

        $beforeAfterItems = $request->input('before_after', []);
        foreach ($beforeAfterItems as $index => $item) {
            if (empty($item['before_media_id']) && empty($item['after_media_id'])) {
                continue;
            }
            ProjectBeforeAfter::create([
                'project_id' => $project->id,
                'before_media_id' => $item['before_media_id'] ?? null,
                'after_media_id' => $item['after_media_id'] ?? null,
                'title' => $item['title'] ?? null,
                'description' => $item['description'] ?? null,
                'display_order' => $index + 1,
            ]);
        }

        $timelines = $request->input('timelines', []);
        foreach ($timelines as $index => $item) {
            if (empty($item['title'])) {
                continue;
            }
            ProjectTimeline::create([
                'project_id' => $project->id,
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'timeline_date' => $item['timeline_date'] ?? null,
                'media_id' => $item['media_id'] ?? null,
                'display_order' => $index + 1,
                'status' => true,
            ]);
        }

        $materials = $request->input('materials', []);
        foreach ($materials as $index => $item) {
            if (empty($item['name'])) {
                continue;
            }
            ProjectMaterial::create([
                'project_id' => $project->id,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'display_order' => $index + 1,
            ]);
        }

        $technologies = $request->input('technologies', []);
        foreach ($technologies as $index => $item) {
            if (empty($item['name'])) {
                continue;
            }
            ProjectTechnology::create([
                'project_id' => $project->id,
                'name' => $item['name'],
                'description' => $item['description'] ?? null,
                'display_order' => $index + 1,
            ]);
        }

        $teamMembers = $request->input('team_members', []);
        foreach ($teamMembers as $index => $item) {
            if (empty($item['team_member_id'])) {
                continue;
            }
            ProjectTeamMember::create([
                'project_id' => $project->id,
                'team_member_id' => $item['team_member_id'],
                'designation' => $item['designation'] ?? null,
                'display_order' => $index + 1,
            ]);
        }

        $relatedProjects = $request->input('related_project_ids', []);
        $project->relatedProjects()->sync($relatedProjects);
    }

    private function imageOptions()
    {
        return Media::where('is_image', true)->where('status', true)->latest()->get();
    }
}
