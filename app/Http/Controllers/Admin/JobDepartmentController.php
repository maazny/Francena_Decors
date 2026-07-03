<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobDepartmentRequest;
use App\Http\Requests\UpdateJobDepartmentRequest;
use App\Models\JobDepartment;
use App\Services\JobDepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobDepartmentController extends Controller
{
    protected $departmentService;

    public function __construct(JobDepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * Display a listing of departments.
     */
    public function index(): View
    {
        $departments = JobDepartment::withTrashed()->orderBy('display_order')->paginate(10);
        return view('admin.careers.departments.index', compact('departments'));
    }

    /**
     * Store a newly created department.
     */
    public function store(StoreJobDepartmentRequest $request): RedirectResponse
    {
        $this->departmentService->create($request->validated());
        return redirect()->route('admin.careers.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Update the specified department.
     */
    public function update(UpdateJobDepartmentRequest $request, JobDepartment $department): RedirectResponse
    {
        $this->departmentService->update($department, $request->validated());
        return redirect()->route('admin.careers.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Soft delete the specified department.
     */
    public function destroy(JobDepartment $department): RedirectResponse
    {
        $department->delete();
        return redirect()->route('admin.careers.departments.index')->with('success', 'Department soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted department.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->departmentService->restore($id);
        return redirect()->route('admin.careers.departments.index')->with('success', 'Department restored successfully.');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(JobDepartment $department): JsonResponse
    {
        $this->departmentService->toggleStatus($department);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Bulk delete departments.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->departmentService->bulkDelete($ids);
        return response()->json(['success' => true, 'message' => "$count departments deleted successfully."]);
    }

    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $count = $this->departmentService->bulkStatus($ids, $status);
        return response()->json(['success' => true, 'message' => "$count departments status updated successfully."]);
    }
}
