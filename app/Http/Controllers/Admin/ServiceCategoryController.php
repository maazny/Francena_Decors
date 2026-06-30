<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Models\Media;
use App\Models\ServiceCategory;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categories = ServiceCategory::withCount('services')
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.service-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.service-categories.create', [
            'serviceCategory' => new ServiceCategory([
                'display_order' => ServiceCategory::withTrashed()->max('display_order') + 1,
                'status' => true,
            ]),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function store(StoreServiceCategoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            ServiceCategory::create($this->payload($request));
        });

        ServiceCatalogService::clearCache();

        return redirect()->route('admin.service-categories.index')->with('success', 'Service category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('admin.service-categories.edit', [
            'serviceCategory' => $serviceCategory->load(['bannerImage', 'featuredImage']),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        DB::transaction(function () use ($request, $serviceCategory): void {
            $serviceCategory->update($this->payload($request));
        });

        ServiceCatalogService::clearCache(category: $serviceCategory);

        return redirect()->route('admin.service-categories.edit', $serviceCategory)->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->delete();
        ServiceCatalogService::clearCache(category: $serviceCategory);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service category deleted successfully.');
    }

    public function restore(int $serviceCategory): RedirectResponse
    {
        $category = ServiceCategory::withTrashed()->findOrFail($serviceCategory);
        $category->restore();
        ServiceCatalogService::clearCache(category: $category);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service category restored successfully.');
    }

    public function toggleStatus(ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update(['status' => ! $serviceCategory->status]);
        ServiceCatalogService::clearCache(category: $serviceCategory);

        return redirect()->route('admin.service-categories.index')->with('success', 'Service category status updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = ServiceCategory::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        ServiceCatalogService::clearCache();

        return redirect()->route('admin.service-categories.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreServiceCategoryRequest $request): array
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
