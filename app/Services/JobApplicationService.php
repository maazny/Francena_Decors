<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class JobApplicationService
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function store(array $data, ?UploadedFile $resume = null): JobApplication
    {
        return DB::transaction(function () use ($data, $resume) {
            if ($resume) {
                // Find first admin or user ID 1 to associate upload owner
                $adminId = User::first()?->id ?? 1;
                $media = $this->mediaService->storeFile($resume, 'resumes', $adminId);
                $data['resume_media_id'] = $media->id;
            }

            $data['applied_at'] = now();
            $data['application_status'] = $data['application_status'] ?? 'applied';

            return JobApplication::create($data);
        });
    }

    public function update(JobApplication $application, array $data): JobApplication
    {
        $application->update($data);
        return $application;
    }

    public function toggleStatus(JobApplication $application): JobApplication
    {
        // Toggle between applied and reviewed as a simple status toggle helper
        $newStatus = $application->application_status === 'applied' ? 'reviewed' : 'applied';
        $application->update([
            'application_status' => $newStatus,
        ]);
        return $application;
    }

    public function restore(int $id): bool
    {
        $application = JobApplication::onlyTrashed()->findOrFail($id);
        return $application->restore();
    }

    public function bulkDelete(array $ids): int
    {
        return JobApplication::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status): int
    {
        return JobApplication::whereIn('id', $ids)->update(['application_status' => $status]);
    }
}
