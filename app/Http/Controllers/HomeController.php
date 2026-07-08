<?php

namespace App\Http\Controllers;

use App\Models\ServiceFaq;
use App\Models\BlogPost;
use App\Models\JobOpening;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\ClientBrand;
use App\Models\Service;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'serviceFaqs' => ServiceFaq::active()->ordered()->take(6)->get(),
            'latestPosts' => Cache::remember('blog.homepage_posts', 3600, function() {
                return BlogPost::where('status', true)
                    ->where('published_at', '<=', now())
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            }),
            'homepageJobs' => Cache::remember('careers.homepage_jobs', 3600, function() {
                return JobOpening::with('location')
                    ->active()
                    ->published()
                    ->homepageFeatured()
                    ->ordered()
                    ->take(3)
                    ->get();
            }),
            'featuredProjects' => Cache::remember('projects.homepage_projects', 3600, function() {
                return Project::with(['category', 'coverImage'])
                    ->published()
                    ->homepageFeatured()
                    ->ordered()
                    ->take(6)
                    ->get();
            }),
            'testimonials' => Cache::remember('testimonials.homepage_testimonials', 3600, function() {
                return Testimonial::with(['clientPhoto', 'clientLogo'])
                    ->homepageFeatured()
                    ->ordered()
                    ->take(6)
                    ->get();
            }),
            'clientBrands' => Cache::remember('client_brands.homepage', 3600, function() {
                return ClientBrand::with('logo')
                    ->homepageFeatured()
                    ->ordered()
                    ->get();
            }),
            'services' => Cache::remember('services.homepage', 3600, function() {
                return Service::with(['category', 'featuredImage'])
                    ->active()
                    ->featured()
                    ->ordered()
                    ->take(12)
                    ->get();
            }),
        ]);
    }
}
