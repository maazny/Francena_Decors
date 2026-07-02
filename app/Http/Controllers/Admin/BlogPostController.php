<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Services\BlogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    protected BlogService $service;

    public function __construct(BlogService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $posts = BlogPost::with(['category', 'author'])
            ->withTrashed()
            ->when($search, fn($q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status !== null, fn($q) => $q->where('status', $status))
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-posts.index', compact('posts', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.blog-posts.create', [
            'post' => new BlogPost(['display_order' => BlogPost::withTrashed()->max('display_order') + 1, 'status' => false, 'is_featured' => false, 'is_homepage_featured' => false]),
        ]);
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->service->generateUniqueSlug($data['title']);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_homepage_featured'] = $request->boolean('is_homepage_featured');
        $data['status'] = $request->boolean('status');
        $data['reading_time'] = $this->service->estimateReadingTime($data['content'] ?? null);

        $post = BlogPost::create($data);

        if (! empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(BlogPost $blog_post): View
    {
        return view('admin.blog-posts.edit', ['post' => $blog_post->load(['tags'])]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blog_post): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->service->generateUniqueSlug($data['title'], $blog_post->id);
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_homepage_featured'] = $request->boolean('is_homepage_featured');
        $data['status'] = $request->boolean('status');
        $data['reading_time'] = $this->service->estimateReadingTime($data['content'] ?? null);

        $blog_post->update($data);

        $blog_post->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.blog-posts.edit', $blog_post)->with('success', 'Post updated successfully.');
    }

    public function destroy(BlogPost $blog_post): RedirectResponse
    {
        $blog_post->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post deleted successfully.');
    }

    public function restore(int $blogPost): RedirectResponse
    {
        $post = BlogPost::withTrashed()->findOrFail($blogPost);
        $post->restore();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post restored successfully.');
    }

    public function toggleStatus(BlogPost $blog_post): RedirectResponse
    {
        $blog_post->update(['status' => ! $blog_post->status]);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post status updated successfully.');
    }

    public function duplicate(BlogPost $blog_post): RedirectResponse
    {
        $copy = $blog_post->replicate();
        $copy->title = $blog_post->title.' Copy';
        $copy->slug = $this->service->generateUniqueSlug($blog_post->slug.'-copy');
        $copy->status = false;
        $copy->is_featured = false;
        $copy->is_homepage_featured = false;
        $copy->display_order = BlogPost::withTrashed()->max('display_order') + 1;
        $copy->save();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Post duplicated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

            \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $query = BlogPost::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        return redirect()->route('admin.blog-posts.index')->with('success', 'Bulk action completed successfully.');
    }
}
