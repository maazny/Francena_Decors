<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $categories = BlogCategory::withCount('posts')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->withTrashed()
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.blog-categories.create', [
            'category' => new BlogCategory(['display_order' => BlogCategory::withTrashed()->max('display_order') + 1, 'status' => true]),
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blog_category): View
    {
        return view('admin.blog-categories.edit', [
            'category' => $blog_category,
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blog_category): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $blog_category->update($data);

        return redirect()->route('admin.blog-categories.edit', $blog_category)->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blog_category): RedirectResponse
    {
        $blog_category->delete();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category deleted successfully.');
    }

    public function restore(int $blog_category): RedirectResponse
    {
        $category = BlogCategory::withTrashed()->findOrFail($blog_category);
        $category->restore();

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category restored successfully.');
    }

    public function toggleStatus(BlogCategory $blog_category): RedirectResponse
    {
        $blog_category->update(['status' => ! $blog_category->status]);

        return redirect()->route('admin.blog-categories.index')->with('success', 'Category status updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $query = BlogCategory::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        return redirect()->route('admin.blog-categories.index')->with('success', 'Bulk action completed successfully.');
    }
}
