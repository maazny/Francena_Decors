<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Media;
use App\Services\MediaService;
use App\Http\Resources\Api\V1\MediaResource;
use App\Services\ActivityLogService;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MediaController extends ApiController
{
    /**
     * @var MediaService
     */
    protected $mediaService;

    /**
     * @var ActivityLogService
     */
    protected $activityLogger;

    /**
     * MediaController constructor.
     */
    public function __construct(MediaService $mediaService, ActivityLogService $activityLogger)
    {
        $this->mediaService = $mediaService;
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display a listing of media files.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Media::query();

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('original_name', 'like', $search)
                  ->orWhere('caption', 'like', $search);
            });
        }

        $this->applyFilters($query, [
            'folder' => 'folder',
            'is_image' => 'is_image',
            'status' => 'status',
        ]);

        $this->applySorting($query, ['title', 'file_size', 'created_at'], '-created_at');

        $perPage = (int) $request->input('per_page', config('api.pagination.default_per_page', 15));
        $maxPerPage = config('api.pagination.max_per_page', 100);
        $perPage = min($perPage, $maxPerPage);

        $paginator = $query->paginate($perPage);

        return $this->paginatedResponse($paginator, MediaResource::class, 'Media items retrieved successfully');
    }

    /**
     * Upload a new media file.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'],
            'folder' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('file');
        $folder = $request->input('folder', 'general');
        $userId = auth()->id() ?: 1;

        $media = $this->mediaService->storeFile($file, $folder, $userId);

        $this->activityLogger->log([
            'user_id' => $userId,
            'module' => 'media',
            'action' => ActivityAction::MEDIA_UPLOAD,
            'description' => "Uploaded file '{$media->original_name}' via API",
            'model_type' => Media::class,
            'model_id' => $media->id,
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->created(new MediaResource($media), 'File uploaded successfully');
    }

    /**
     * Delete specific media file.
     */
    public function destroy(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        $fileName = $media->original_name;

        $this->mediaService->delete($media);

        $this->activityLogger->log([
            'user_id' => auth()->id(),
            'module' => 'media',
            'action' => ActivityAction::MEDIA_DELETE,
            'description' => "Deleted file '{$fileName}' via API",
            'status' => ActivityStatus::SUCCESS,
        ]);

        return $this->success(null, 'Media item deleted successfully');
    }
}
