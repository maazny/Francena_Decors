<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectCategoryRequest;
use App\Http\Requests\UpdateProjectCategoryRequest;
use App\Models\Media;
use App\Models\ProjectCategory;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categories = ProjectCategory::withCount('projects')
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.project-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.project-categories.create', [
            'projectCategory' => new ProjectCategory([
                'display_order' => ProjectCategory::withTrashed()->max('display_order') + 1,
                'status' => true,
            ]),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function store(StoreProjectCategoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            ProjectCategory::create($this->payload($request));
        });

        ProjectService::clearCache();

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category created successfully.');
    }

    public function edit(ProjectCategory $projectCategory): View
    {
        return view('admin.project-categories.edit', [
            'projectCategory' => $projectCategory->load(['bannerImage', 'featuredImage']),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function update(UpdateProjectCategoryRequest $request, ProjectCategory $projectCategory): RedirectResponse
    {
        DB::transaction(function () use ($request, $projectCategory): void {
            $projectCategory->update($this->payload($request));
        });

        ProjectService::clearCache(category: $projectCategory);

        return redirect()->route('admin.project-categories.edit', $projectCategory)->with('success', 'Project category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->delete();
        ProjectService::clearCache(category: $projectCategory);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category deleted successfully.');
    }

    public function restore(int $projectCategory): RedirectResponse
    {
        $category = ProjectCategory::withTrashed()->findOrFail($projectCategory);
        $category->restore();
        ProjectService::clearCache(category: $category);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category restored successfully.');
    }

    public function toggleStatus(ProjectCategory $projectCategory): RedirectResponse
    {
        $projectCategory->update(['status' => ! $projectCategory->status]);
        ProjectService::clearCache(category: $projectCategory);

        return redirect()->route('admin.project-categories.index')->with('success', 'Project category status updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = ProjectCategory::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        ProjectService::clearCache();

        return redirect()->route('admin.project-categories.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreProjectCategoryRequest $request): array
    {
        $data = $request->validated();
        $data['slug'] = filled($data['slug'] ?? null) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        return $data;
    }

    private function imageOptions()
    {
        return Media::where('is_image', true)->where('status', true)->latest()->get();
    }
}
