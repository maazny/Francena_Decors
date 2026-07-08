<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BlogPost;
use App\Services\BlogService;
use App\Http\Resources\Api\V1\BlogResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogController extends ApiController
{
    /**
     * @var BlogService
     */
    protected $blogService;

    /**
     * BlogController constructor.
     */
    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of blog articles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BlogPost::with(['category', 'author', 'featuredImage', 'tags'])->active();

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('excerpt', 'like', $search)
                  ->orWhere('content', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'category_id' => 'category_id',
            'author_id' => 'author_id',
            'is_featured' => 'is_featured',
            'is_homepage_featured' => 'is_homepage_featured',
        ]);

        if ($request->filled('tag')) {
            $tagSlug = $request->tag;
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        $this->applySorting($query, ['title', 'published_at', 'reading_time', 'display_order', 'created_at'], '-published_at');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, BlogResource::class, 'Blog posts retrieved successfully');
    }

    /**
     * Display blog article details by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::with(['category', 'author', 'featuredImage', 'bannerImage', 'tags', 'galleries.media'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->success(new BlogResource($post), 'Blog post details retrieved successfully');
    }
}
