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

            $application = JobApplication::create($data);

            // Clear statistics cache
            JobOpeningService::clearCache();

            // Dispatch Notifications
            try {
                // 1. Notify Candidate
                \Illuminate\Support\Facades\Notification::route('mail', $application->email)
                    ->notify(new \App\Notifications\ApplicationReceivedNotification($application));

                // 2. Notify Admin
                $admin = User::first();
                if ($admin) {
                    $admin->notify(new \App\Notifications\NewJobApplicationNotification($application));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending application notification: " . $e->getMessage());
            }

            // Log activity
            \Illuminate\Support\Facades\Log::info("Application Submitted: [ID: {$application->id}] {$application->full_name} for Job ID {$application->job_opening_id}");

            return $application;
        });
    }

    public function update(JobApplication $application, array $data): JobApplication
    {
        $oldStatus = $application->application_status;
        $application->update($data);
        $newStatus = $application->application_status;

        // Clear statistics cache
        JobOpeningService::clearCache();

        if ($oldStatus !== $newStatus) {
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $application->email)
                    ->notify(new \App\Notifications\ApplicationStatusUpdatedNotification($application));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending application status update notification: " . $e->getMessage());
            }
            \Illuminate\Support\Facades\Log::info("Application Status Changed: [ID: {$application->id}] by Update to {$newStatus}");
        }

        return $application;
    }

    public function toggleStatus(JobApplication $application): JobApplication
    {
        $oldStatus = $application->application_status;
        $newStatus = $oldStatus === 'applied' ? 'reviewed' : 'applied';
        $application->update([
            'application_status' => $newStatus,
        ]);

        JobOpeningService::clearCache();

        try {
            \Illuminate\Support\Facades\Notification::route('mail', $application->email)
                ->notify(new \App\Notifications\ApplicationStatusUpdatedNotification($application));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed sending application status toggle notification: " . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info("Application Status Changed: [ID: {$application->id}] by Toggle to {$newStatus}");

        return $application;
    }

    public function restore(int $id): bool
    {
        $application = JobApplication::onlyTrashed()->findOrFail($id);
        $res = $application->restore();
        JobOpeningService::clearCache();
        \Illuminate\Support\Facades\Log::info("Application Restored: [ID: {$application->id}]");
        return $res;
    }

    public function bulkDelete(array $ids): int
    {
        $count = JobApplication::whereIn('id', $ids)->delete();
        JobOpeningService::clearCache();
        \Illuminate\Support\Facades\Log::info("Applications Deleted (Bulk): count {$count}");
        return $count;
    }

    public function bulkStatus(array $ids, string $status): int
    {
        $applications = JobApplication::whereIn('id', $ids)->get();
        $count = JobApplication::whereIn('id', $ids)->update(['application_status' => $status]);
        
        JobOpeningService::clearCache();

        foreach ($applications as $application) {
            $application->application_status = $status; // update local representation for notification context
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $application->email)
                    ->notify(new \App\Notifications\ApplicationStatusUpdatedNotification($application));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed sending bulk application status notification: " . $e->getMessage());
            }
            \Illuminate\Support\Facades\Log::info("Application Status Changed: [ID: {$application->id}] by Bulk Action to {$status}");
        }

        return $count;
    }
}
