<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientBrandRequest;
use App\Http\Requests\UpdateClientBrandRequest;
use App\Models\ClientBrand;
use App\Models\Media;
use App\Services\ClientBrandService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClientBrandController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $category = $request->query('category');
        $featured = $request->query('featured');

        $brands = ClientBrand::query()
            ->with('logo')
            ->withTrashed()
            ->when($search, fn ($query) => $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', $status))
            ->when($category !== null && $category !== '', fn ($query) => $query->where('category', $category))
            ->when($featured !== null && $featured !== '', fn ($query) => $query->where('featured', (bool) $featured))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.client-brands.index', [
            'brands' => $brands,
            'search' => $search,
            'status' => $status,
            'category' => $category,
            'featured' => $featured,
            'categories' => ClientBrandService::getCategories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.client-brands.create', [
            'brand' => new ClientBrand([
                'display_order' => ClientBrand::withTrashed()->max('display_order') + 1,
                'status' => 'draft',
                'featured' => false,
                'homepage_featured' => false,
            ]),
            'mediaOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function store(StoreClientBrandRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            ClientBrand::create($this->payload($request));
        });

        ClientBrandService::clearCache();

        return redirect()->route('admin.client-brands.index')->with('success', 'Client brand created successfully.');
    }

    public function edit(ClientBrand $clientBrand): View
    {
        return view('admin.client-brands.edit', [
            'brand' => $clientBrand->load('logo'),
            'mediaOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function update(UpdateClientBrandRequest $request, ClientBrand $clientBrand): RedirectResponse
    {
        DB::transaction(function () use ($request, $clientBrand): void {
            $clientBrand->update($this->payload($request));
        });

        ClientBrandService::clearCache($clientBrand);

        return redirect()->route('admin.client-brands.edit', $clientBrand)->with('success', 'Client brand updated successfully.');
    }

    public function destroy(ClientBrand $clientBrand): RedirectResponse
    {
        $clientBrand->delete();
        ClientBrandService::clearCache($clientBrand);

        return redirect()->route('admin.client-brands.index')->with('success', 'Client brand deleted successfully.');
    }

    public function restore(int $clientBrand): RedirectResponse
    {
        $brand = ClientBrand::withTrashed()->findOrFail($clientBrand);
        $brand->restore();
        ClientBrandService::clearCache($brand);

        return redirect()->route('admin.client-brands.index')->with('success', 'Client brand restored successfully.');
    }

    public function toggleStatus(ClientBrand $clientBrand): RedirectResponse
    {
        $newStatus = $clientBrand->status === 'published' ? 'draft' : 'published';
        $clientBrand->update(['status' => $newStatus]);
        ClientBrandService::clearCache($clientBrand);

        return redirect()->route('admin.client-brands.index')->with('success', 'Client brand status updated successfully.');
    }

    public function duplicate(ClientBrand $clientBrand): RedirectResponse
    {
        DB::transaction(function () use ($clientBrand): void {
            $copy = $clientBrand->replicate();
            $copy->name = $clientBrand->name.' Copy';
            $copy->slug = null;
            $copy->status = 'draft';
            $copy->featured = false;
            $copy->homepage_featured = false;
            $copy->display_order = ClientBrand::withTrashed()->max('display_order') + 1;
            $copy->save();
        });

        ClientBrandService::clearCache();

        return redirect()->route('admin.client-brands.index')->with('success', 'Client brand duplicated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:publish,draft,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = ClientBrand::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'publish' => $query->update(['status' => 'published']),
                'draft' => $query->update(['status' => 'draft']),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        ClientBrandService::clearCache();

        return redirect()->route('admin.client-brands.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreClientBrandRequest|UpdateClientBrandRequest $request): array
    {
        $data = $request->validated();
        $data['slug'] = ClientBrandService::makeSlug($request->input('name'), $request->input('slug'));
        $data['featured'] = $request->boolean('featured');
        $data['homepage_featured'] = $request->boolean('homepage_featured');

        foreach ($data as $field => $value) {
            if ($value === '') {
                $data[$field] = null;
            }
        }

        return $data;
    }
}
