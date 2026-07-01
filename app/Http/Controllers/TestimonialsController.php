<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\TestimonialCategory;
use App\Services\TestimonialService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categoryId = $request->query('category');
        $rating = $request->query('rating');
        $location = $request->query('location');

        $query = Testimonial::query()
            ->with(['category', 'clientPhoto', 'clientLogo', 'project'])
            ->published();

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('client_company', 'like', "%{$search}%")
                    ->orWhere('testimonial', 'like', "%{$search}%");
            });
        }

        if (filled($categoryId)) {
            $query->where('testimonial_category_id', $categoryId);
        }

        if (filled($rating)) {
            $query->where('rating', $rating);
        }

        if (filled($location)) {
            $query->where('location', 'like', "%{$location}%");
        }

        $testimonials = $query->ordered()->paginate(12)->withQueryString();

        return view('testimonials.index', [
            'testimonials' => $testimonials,
            'categories' => TestimonialService::getCategories(),
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedRating' => $rating,
            'selectedLocation' => $location,
        ]);
    }

    public function show(Testimonial $testimonial): View
    {
        if ($testimonial->status !== 'published' || ! $testimonial->isApproved()) {
            abort(404);
        }

        return view('testimonials.show', [
            'testimonial' => $testimonial->load(['category', 'clientPhoto', 'clientLogo', 'project']),
            'relatedTestimonials' => Testimonial::query()
                ->with(['category', 'clientPhoto', 'clientLogo'])
                ->published()
                ->where('id', '!=', $testimonial->id)
                ->where('testimonial_category_id', $testimonial->testimonial_category_id)
                ->ordered()
                ->limit(3)
                ->get(),
        ]);
    }

    public function byCategory(TestimonialCategory $testimonialCategory, Request $request): View
    {
        if (! $testimonialCategory->status) {
            abort(404);
        }

        $search = trim((string) $request->query('search'));
        $rating = $request->query('rating');

        $query = Testimonial::query()
            ->with(['category', 'clientPhoto', 'clientLogo', 'project'])
            ->where('testimonial_category_id', $testimonialCategory->id)
            ->published();

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                    ->orWhere('client_company', 'like', "%{$search}%")
                    ->orWhere('testimonial', 'like', "%{$search}%");
            });
        }

        if (filled($rating)) {
            $query->where('rating', $rating);
        }

        $testimonials = $query->ordered()->paginate(12)->withQueryString();

        return view('testimonials.category', [
            'category' => $testimonialCategory,
            'testimonials' => $testimonials,
            'search' => $search,
            'selectedRating' => $rating,
        ]);
    }
}
