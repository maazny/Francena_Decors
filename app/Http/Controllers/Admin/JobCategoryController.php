<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobCategoryRequest;
use App\Http\Requests\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use App\Models\JobDepartment;
use App\Services\JobCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(JobCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = JobCategory::with('department')->withTrashed()->orderBy('display_order')->paginate(10);
        $departments = JobDepartment::active()->ordered()->get();
        return view('admin.careers.categories.index', compact('categories', 'departments'));
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreJobCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());
        return redirect()->route('admin.careers.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateJobCategoryRequest $request, JobCategory $category): RedirectResponse
    {
        $this->categoryService->update($category, $request->validated());
        return redirect()->route('admin.careers.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Soft delete the specified category.
     */
    public function destroy(JobCategory $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.careers.categories.index')->with('success', 'Category soft deleted successfully.');
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->categoryService->restore($id);
        return redirect()->route('admin.careers.categories.index')->with('success', 'Category restored successfully.');
    }

    /**
     * Toggle status.
     */
    public function toggleStatus(JobCategory $category): JsonResponse
    {
        $this->categoryService->toggleStatus($category);
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->categoryService->bulkDelete($ids);
        return response()->json(['success' => true, 'message' => "$count categories deleted successfully."]);
    }

    /**
     * Bulk status update.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = (bool) $request->input('status', true);
        $count = $this->categoryService->bulkStatus($ids, $status);
        return response()->json(['success' => true, 'message' => "$count categories status updated successfully."]);
    }
}
