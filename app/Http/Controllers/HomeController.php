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
            'latestPosts' => \App\Models\BlogPost::where('status', true)
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
