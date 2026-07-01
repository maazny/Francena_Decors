<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTestimonialRequest;
use App\Http\Requests\UpdateTestimonialRequest;
use App\Models\Media;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\TestimonialCategory;
use App\Services\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $categoryId = $request->query('category_id');
        $rating = $request->query('rating');

        $testimonials = Testimonial::with(['category', 'clientPhoto', 'clientLogo', 'project'])
            ->withTrashed()
            ->when($search, fn ($query) => $query->where('client_name', 'like', "%{$search}%")
                ->orWhere('client_company', 'like', "%{$search}%")
                ->orWhere('testimonial', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($categoryId, fn ($query) => $query->where('testimonial_category_id', $categoryId))
            ->when($rating, fn ($query) => $query->where('rating', $rating))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'search' => $search,
            'status' => $status,
            'categoryId' => $categoryId,
            'rating' => $rating,
            'categories' => TestimonialCategory::active()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create', [
            'testimonial' => new Testimonial([
                'display_order' => Testimonial::withTrashed()->max('display_order') + 1,
                'status' => 'draft',
                'rating' => 5,
                'featured' => false,
                'homepage_featured' => false,
            ]),
            'categories' => TestimonialCategory::active()->ordered()->get(),
            'projects' => Project::where('status', 'published')->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function store(StoreTestimonialRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            Testimonial::create($this->payload($request));
        });

        TestimonialService::clearCache();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial->load(['category', 'clientPhoto', 'clientLogo', 'project']),
            'categories' => TestimonialCategory::active()->ordered()->get(),
            'projects' => Project::where('status', 'published')->ordered()->get(),
            'imageOptions' => $this->imageOptions(),
        ]);
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        DB::transaction(function () use ($request, $testimonial): void {
            $testimonial->update($this->payload($request));
        });

        TestimonialService::clearCache(testimonial: $testimonial);

        return redirect()->route('admin.testimonials.edit', $testimonial)->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        TestimonialService::clearCache(testimonial: $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function restore(int $testimonial): RedirectResponse
    {
        $testimonial = Testimonial::withTrashed()->findOrFail($testimonial);
        $testimonial->restore();
        TestimonialService::clearCache(testimonial: $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial restored successfully.');
    }

    public function toggleStatus(Testimonial $testimonial): RedirectResponse
    {
        $newStatus = $testimonial->status === 'published' ? 'draft' : 'published';
        $testimonial->update([
            'status' => $newStatus,
            'approved_at' => $newStatus === 'published' ? now() : null,
        ]);
        TestimonialService::clearCache(testimonial: $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial status updated successfully.');
    }

    public function duplicate(Testimonial $testimonial): RedirectResponse
    {
        DB::transaction(function () use ($testimonial): void {
            $newTestimonial = $testimonial->replicate();
            $newTestimonial->display_order = Testimonial::withTrashed()->max('display_order') + 1;
            $newTestimonial->status = 'draft';
            $newTestimonial->featured = false;
            $newTestimonial->homepage_featured = false;
            $newTestimonial->approved_at = null;
            $newTestimonial->save();
        });

        TestimonialService::clearCache();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial duplicated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:publish,draft,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = Testimonial::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'publish' => $query->update(['status' => 'published', 'approved_at' => now()]),
                'draft' => $query->update(['status' => 'draft']),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        TestimonialService::clearCache();

        return redirect()->route('admin.testimonials.index')->with('success', 'Bulk action completed successfully.');
    }

    private function payload(StoreTestimonialRequest|UpdateTestimonialRequest $request): array
    {
        $data = $request->validated();
        $data['featured'] = $request->boolean('featured');
        $data['homepage_featured'] = $request->boolean('homepage_featured');

        if ($data['status'] === 'published' && ! filled($data['approved_at'])) {
            $data['approved_at'] = now();
        }

        return $data;
    }

    private function imageOptions()
    {
        return Media::where('is_image', true)->where('status', true)->latest()->get();
    }
}
