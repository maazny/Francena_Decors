<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Media;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $services = Service::with(['category', 'featuredImage'])
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status !== null, fn ($query) => $query->where('status', $status))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.services.index', compact('services', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.services.create', [
            'service' => new Service([
                'display_order' => Service::withTrashed()->max('display_order') + 1,
                'button_one_target' => '_self',
                'status' => true,
            ]),
            'categories' => ServiceCategory::active()->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            Service::create($this->payload($request));
        });

        ServiceCatalogService::clearCache();

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', [
            'service' => $service->load(['category', 'featuredImage', 'bannerImage', 'features', 'processes', 'faqs']),
            'categories' => ServiceCategory::active()->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        DB::transaction(function () use ($request, $service): void {
            $service->update($this->payload($request));
        });

        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.edit', $service)->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    public function restore(int $service): RedirectResponse
    {
        $service = Service::withTrashed()->findOrFail($service);
        $service->restore();
        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.index')->with('success', 'Service restored successfully.');
    }

    public function toggleStatus(Service $service): RedirectResponse
    {
        $service->update(['status' => ! $service->status]);
        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.index')->with('success', 'Service status updated successfully.');
    }

    public function duplicate(Service $service): RedirectResponse
    {
        DB::transaction(function () use ($service): void {
            $copy = $service->replicate();
            $copy->title = $service->title.' Copy';
            $copy->slug = $service->slug.'-copy-'.now()->timestamp;
            $copy->status = false;
            $copy->is_featured = false;
            $copy->display_order = Service::withTrashed()->max('display_order') + 1;
            $copy->save();
        });

        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.index')->with('success', 'Service duplicated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = Service::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        ServiceCatalogService::clearCache();

        return redirect()->route('admin.services.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreServiceRequest $request): array
    {
        $data = $request->validated();
        $data['slug'] = filled($data['slug'] ?? null) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['status'] = $request->boolean('status');

        return $data;
    }

    private function imageOptions()
    {
        return Media::where('is_image', true)->where('status', true)->latest()->get();
    }
}
