<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobOpeningRequest;
use App\Http\Requests\UpdateJobOpeningRequest;
use App\Models\JobOpening;
use App\Models\JobDepartment;
use App\Models\JobCategory;
use App\Models\JobLocation;
use App\Services\JobOpeningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOpeningController extends Controller
{
    protected $jobOpeningService;

    public function __construct(JobOpeningService $jobOpeningService)
    {
        $this->jobOpeningService = $jobOpeningService;
    }

    /**
     * Display a listing of job openings.
     */
    public function index(): View
    {
        $jobs = JobOpening::with(['department', 'category', 'location'])
            ->withTrashed()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.careers.jobs.index', compact('jobs'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        $departments = JobDepartment::active()->ordered()->get();
        $categories = JobCategory::active()->ordered()->get();
        $locations = JobLocation::active()->ordered()->get();

        return view('admin.careers.jobs.create', compact('departments', 'categories', 'locations'));
    }

    /**
     * Store new job opening.
     */
    public function store(StoreJobOpeningRequest $request): RedirectResponse
    {
        $this->jobOpeningService->create($request->validated());
        return redirect()->route('admin.careers.jobs.index')->with('success', 'Job opening created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(JobOpening $job): View
    {
        $departments = JobDepartment::active()->ordered()->get();
        $categories = JobCategory::active()->ordered()->get();
        $locations = JobLocation::active()->ordered()->get();

        $job->load(['skills', 'benefits', 'qualifications']);

        return view('admin.careers.jobs.edit', compact('job', 'departments', 'categories', 'locations'));
    }

    /**
     * Update job opening.
     */
    public function update(UpdateJobOpeningRequest $request, JobOpening $job): RedirectResponse
    {
        $this->jobOpeningService->update($job, $request->validated());
        return redirect()->route('admin.careers.jobs.index')->with('success', 'Job opening updated successfully.');
    }

    /**
     * Soft delete job opening.
     */
    public function destroy(JobOpening $job): RedirectResponse
    {
        $job->delete();
        return redirect()->route('admin.careers.jobs.index')->with('success', 'Job opening soft deleted successfully.');
    }

    /**
     * Restore job opening.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->jobOpeningService->restore($id);
        return redirect()->route('admin.careers.jobs.index')->with('success', 'Job opening restored successfully.');
    }

    /**
     * Duplicate job opening.
     */
    public function duplicate(JobOpening $job): RedirectResponse
    {
        $copy = $this->jobOpeningService->duplicate($job);
        return redirect()->route('admin.careers.jobs.index')->with('success', "Job opening duplicated successfully as draft: {$copy->title}");
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(JobOpening $job): JsonResponse
    {
        $this->jobOpeningService->toggleStatus($job);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Toggle featured.
     */
    public function toggleFeatured(JobOpening $job): JsonResponse
    {
        $this->jobOpeningService->toggleFeatured($job);
        return response()->json(['success' => true, 'message' => 'Featured status updated successfully.']);
    }

    /**
     * Toggle homepage featured.
     */
    public function toggleHomepageFeatured(JobOpening $job): JsonResponse
    {
        $this->jobOpeningService->toggleHomepageFeatured($job);
        return response()->json(['success' => true, 'message' => 'Homepage featured status updated successfully.']);
    }

    /**
     * Bulk delete.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->jobOpeningService->bulkDelete($ids);
        return response()->json(['success' => true, 'message' => "$count job openings deleted successfully."]);
    }

    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $count = $this->jobOpeningService->bulkStatus($ids, $status);
        return response()->json(['success' => true, 'message' => "$count job openings status updated successfully."]);
    }
}
