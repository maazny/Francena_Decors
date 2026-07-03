<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Http\Requests\UpdateJobApplicationRequest;
use App\Models\JobApplication;
use App\Models\JobOpening;
use App\Services\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(JobApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    /**
     * Display a listing of applications.
     */
    public function index(Request $request): View
    {
        $query = JobApplication::with(['jobOpening', 'resumeMedia'])->withTrashed();

        if ($request->filled('job_opening_id')) {
            $query->where('job_opening_id', $request->job_opening_id);
        }

        if ($request->filled('status')) {
            $query->where('application_status', $request->status);
        }

        $applications = $query->orderBy('id', 'desc')->paginate(10);
        $jobOpenings = JobOpening::active()->get();

        return view('admin.careers.applications.index', compact('applications', 'jobOpenings'));
    }

    /**
     * Display the specified application.
     */
    public function show(JobApplication $application): View
    {
        $application->load(['jobOpening', 'resumeMedia']);
        return view('admin.careers.applications.show', compact('application'));
    }

    /**
     * Store a newly created application.
     */
    public function store(StoreJobApplicationRequest $request): RedirectResponse
    {
        $this->applicationService->store($request->validated(), $request->file('resume'));
        return redirect()->route('admin.careers.applications.index')->with('success', 'Application submitted successfully.');
    }

    /**
     * Update the specified application.
     */
    public function update(UpdateJobApplicationRequest $request, JobApplication $application): RedirectResponse
    {
        $this->applicationService->update($application, $request->validated());
        return redirect()->route('admin.careers.applications.index')->with('success', 'Application updated successfully.');
    }

    /**
     * Soft delete the specified application.
     */
    public function destroy(JobApplication $application): RedirectResponse
    {
        $application->delete();
        return redirect()->route('admin.careers.applications.index')->with('success', 'Application soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted application.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->applicationService->restore($id);
        return redirect()->route('admin.careers.applications.index')->with('success', 'Application restored successfully.');
    }

    /**
     * Toggle status helper.
     */
    public function toggleStatus(JobApplication $application): JsonResponse
    {
        $this->applicationService->toggleStatus($application);
        return response()->json(['success' => true, 'message' => 'Status toggled successfully.']);
    }

    /**
     * Bulk delete applications.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->applicationService->bulkDelete($ids);
        return response()->json(['success' => true, 'message' => "$count applications deleted successfully."]);
    }

    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status', 'applied');
        $count = $this->applicationService->bulkStatus($ids, $status);
        return response()->json(['success' => true, 'message' => "$count applications status updated successfully."]);
    }
}
