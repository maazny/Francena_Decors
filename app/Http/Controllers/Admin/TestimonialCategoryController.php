<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialCategoryRequest;
use App\Http\Requests\UpdateTestimonialCategoryRequest;
use App\Models\TestimonialCategory;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestimonialCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categories = TestimonialCategory::withCount('testimonials')
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonial-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.testimonial-categories.create', [
            'testimonialCategory' => new TestimonialCategory([
                'display_order' => TestimonialCategory::withTrashed()->max('display_order') + 1,
                'status' => true,
            ]),
        ]);
    }

    public function store(StoreTestimonialCategoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            TestimonialCategory::create($this->payload($request));
        });

        TestimonialService::clearCache();

        return redirect()->route('admin.testimonial-categories.index')->with('success', 'Testimonial category created successfully.');
    }

    public function edit(TestimonialCategory $testimonialCategory): View
    {
        return view('admin.testimonial-categories.edit', [
            'testimonialCategory' => $testimonialCategory,
        ]);
    }

    public function update(UpdateTestimonialCategoryRequest $request, TestimonialCategory $testimonialCategory): RedirectResponse
    {
        DB::transaction(function () use ($request, $testimonialCategory): void {
            $testimonialCategory->update($this->payload($request));
        });

        TestimonialService::clearCache(category: $testimonialCategory);

        return redirect()->route('admin.testimonial-categories.edit', $testimonialCategory)->with('success', 'Testimonial category updated successfully.');
    }

    public function destroy(TestimonialCategory $testimonialCategory): RedirectResponse
    {
        $testimonialCategory->delete();
        TestimonialService::clearCache(category: $testimonialCategory);

        return redirect()->route('admin.testimonial-categories.index')->with('success', 'Testimonial category deleted successfully.');
    }

    public function restore(int $testimonialCategory): RedirectResponse
    {
        $category = TestimonialCategory::withTrashed()->findOrFail($testimonialCategory);
        $category->restore();
        TestimonialService::clearCache(category: $category);

        return redirect()->route('admin.testimonial-categories.index')->with('success', 'Testimonial category restored successfully.');
    }

    public function toggleStatus(TestimonialCategory $testimonialCategory): RedirectResponse
    {
        $testimonialCategory->update(['status' => ! $testimonialCategory->status]);
        TestimonialService::clearCache(category: $testimonialCategory);

        return redirect()->route('admin.testimonial-categories.index')->with('success', 'Testimonial category status updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = TestimonialCategory::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        TestimonialService::clearCache();

        return redirect()->route('admin.testimonial-categories.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreTestimonialCategoryRequest|UpdateTestimonialCategoryRequest $request): array
    {
        $data = $request->validated();
        $data['slug'] = filled($data['slug'] ?? null) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
