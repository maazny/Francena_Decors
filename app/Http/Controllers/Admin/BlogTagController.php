<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogTagRequest;
use App\Http\Requests\UpdateBlogTagRequest;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $tags = BlogTag::withTrashed()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.blog-tags.index', compact('tags', 'search'));
    }

    public function create(): View
    {
        return view('admin.blog-tags.create', ['tag' => new BlogTag(['display_order' => BlogTag::withTrashed()->max('display_order') + 1, 'status' => true])]);
    }

    public function store(StoreBlogTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        BlogTag::create($data);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(BlogTag $blog_tag): View
    {
        return view('admin.blog-tags.edit', ['tag' => $blog_tag]);
    }

    public function update(UpdateBlogTagRequest $request, BlogTag $blog_tag): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $blog_tag->update($data);

        return redirect()->route('admin.blog-tags.edit', $blog_tag)->with('success', 'Tag updated successfully.');
    }

    public function destroy(BlogTag $blog_tag): RedirectResponse
    {
        $blog_tag->delete();

        return redirect()->route('admin.blog-tags.index')->with('success', 'Tag deleted successfully.');
    }

    public function restore(int $blogTag): RedirectResponse
    {
        $tag = BlogTag::withTrashed()->findOrFail($blogTag);
        $tag->restore();

        return redirect()->route('admin.blog-tags.index')->with('success', 'Tag restored successfully.');
    }

    public function toggleStatus(BlogTag $blog_tag): RedirectResponse
    {
        $blog_tag->update(['status' => ! $blog_tag->status]);

        return redirect()->route('admin.blog-tags.index')->with('success', 'Tag status updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $query = BlogTag::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        return redirect()->route('admin.blog-tags.index')->with('success', 'Bulk action completed successfully.');
    }
}
