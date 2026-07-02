<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display public blog posts with filters and search.
     */
    public function index(Request $request): View
    {
        $query = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->with(['category', 'featuredImage', 'author']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->input('tag'));
            });
        }

        // Author filter
        if ($request->filled('author')) {
            $query->where('author_id', $request->input('author'));
        }

        // Archive filter (Format: YYYY-MM)
        if ($request->filled('archive')) {
            $archive = $request->input('archive');
            $parts = explode('-', $archive);
            if (count($parts) === 2) {
                $query->whereYear('published_at', $parts[0])
                      ->whereMonth('published_at', $parts[1]);
            }
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $posts = $query->orderBy('display_order', 'asc')
            ->orderBy('published_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        // Sidebar categories with active post count
        $sidebarCategories = BlogCategory::where('status', true)
            ->withCount(['posts' => function ($q) {
                $q->where('status', true)->where('published_at', '<=', now());
            }])
            ->get();

        // Sidebar tags
        $sidebarTags = BlogTag::where('status', true)->get();

        // Sidebar archives count (database-agnostic)
        $archiveData = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->pluck('published_at');

        $sidebarArchives = $archiveData->groupBy(function ($date) {
            return $date->format('Y-m');
        })->map(function ($group) {
            $first = $group->first();
            return (object) [
                'year' => (int) $first->format('Y'),
                'month' => (int) $first->format('m'),
                'count' => $group->count(),
            ];
        })->values();

        // Featured posts for sidebar/highlights
        $featuredPosts = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.index', compact('posts', 'sidebarCategories', 'sidebarTags', 'sidebarArchives', 'featuredPosts'));
    }

    /**
     * Display a single blog post details page.
     */
    public function show(BlogPost $blog_post): View
    {
        // Prevent previewing draft posts via public show route unless they are logged in or status is true
        if (! $blog_post->status || $blog_post->published_at > now()) {
            if (! auth()->check()) {
                abort(404);
            }
        }

        $post = $blog_post->load(['category', 'tags', 'galleries.media', 'featuredImage', 'bannerImage', 'author']);

        // Related posts in same category, excluding current post
        $relatedPosts = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Popular posts (based on display_order/featured status)
        $popularPosts = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order', 'asc')
            ->take(5)
            ->get();

        // Latest posts
        $latestPosts = BlogPost::where('status', true)
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(5)
            ->get();

        // Categories list for sidebar
        $sidebarCategories = BlogCategory::where('status', true)
            ->withCount(['posts' => function ($q) {
                $q->where('status', true)->where('published_at', '<=', now());
            }])
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'popularPosts', 'latestPosts', 'sidebarCategories'));
    }
}
