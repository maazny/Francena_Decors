<?php

namespace App\Http\Controllers;

use App\Models\ServiceFaq;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'serviceFaqs' => ServiceFaq::active()->ordered()->take(6)->get(),
            'latestPosts' => \Illuminate\Support\Facades\Cache::remember('blog.homepage_posts', 3600, function() {
                return \App\Models\BlogPost::where('status', true)
                    ->where('published_at', '<=', now())
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            }),
            'homepageJobs' => \Illuminate\Support\Facades\Cache::remember('careers.homepage_jobs', 3600, function() {
                return \App\Models\JobOpening::with('location')
                    ->active()
                    ->published()
                    ->homepageFeatured()
                    ->ordered()
                    ->take(3)
                    ->get();
            }),
        ]);
    }
}
