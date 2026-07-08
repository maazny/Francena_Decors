<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Media;
use App\Http\Resources\Api\V1\GalleryResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GalleryController extends ApiController
{
    /**
     * Display a listing of public gallery images.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Media::query()->where('is_image', true)->where('status', true);

        if ($request->filled('folder')) {
            $query->where('folder', $request->folder);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('caption', 'like', $search);
            });
        }

        $this->applySorting($query, ['title', 'created_at', 'file_size'], '-created_at');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, GalleryResource::class, 'Gallery images retrieved successfully');
    }
}
