<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobLocationRequest;
use App\Http\Requests\UpdateJobLocationRequest;
use App\Models\JobLocation;
use App\Services\JobLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobLocationController extends Controller
{
    protected $locationService;

    public function __construct(JobLocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Display a listing of locations.
     */
    public function index(): View
    {
        $locations = JobLocation::withTrashed()->orderBy('display_order')->paginate(10);
        return view('admin.careers.locations.index', compact('locations'));
    }

    /**
     * Store a newly created location.
     */
    public function store(StoreJobLocationRequest $request): RedirectResponse
    {
        $this->locationService->create($request->validated());
        return redirect()->route('admin.careers.locations.index')->with('success', 'Location created successfully.');
    }

    /**
     * Update the specified location.
     */
    public function update(UpdateJobLocationRequest $request, JobLocation $location): RedirectResponse
    {
        $this->locationService->update($location, $request->validated());
        return redirect()->route('admin.careers.locations.index')->with('success', 'Location updated successfully.');
    }

    /**
     * Soft delete the specified location.
     */
    public function destroy(JobLocation $location): RedirectResponse
    {
        $location->delete();
        return redirect()->route('admin.careers.locations.index')->with('success', 'Location soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted location.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->locationService->restore($id);
        return redirect()->route('admin.careers.locations.index')->with('success', 'Location restored successfully.');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(JobLocation $location): JsonResponse
    {
        $this->locationService->toggleStatus($location);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Bulk delete locations.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->locationService->bulkDelete($ids);
        return response()->json(['success' => true, 'message' => "$count locations deleted successfully."]);
    }

    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $count = $this->locationService->bulkStatus($ids, $status);
        return response()->json(['success' => true, 'message' => "$count locations status updated successfully."]);
    }
}
