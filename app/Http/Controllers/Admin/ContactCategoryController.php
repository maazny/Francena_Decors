<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactCategoryRequest;
use App\Http\Requests\UpdateContactCategoryRequest;
use App\Models\ContactCategory;
use App\Services\ContactCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(ContactCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = ContactCategory::withTrashed()->orderBy('display_order')->paginate(10);
        return view('admin.contacts.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreContactCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->create($request->validated());
        return redirect()->route('admin.contacts.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateContactCategoryRequest $request, ContactCategory $category): RedirectResponse
    {
        $this->categoryService->update($category, $request->validated());
        return redirect()->route('admin.contacts.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Soft delete the specified category.
     */
    public function destroy(ContactCategory $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.contacts.categories.index')
            ->with('success', 'Category soft deleted successfully.');
    }

    /**
     * Toggle status active/inactive.
     */
    public function toggleStatus(ContactCategory $category): JsonResponse
    {
        $category = $this->categoryService->toggleStatus($category);
        return response()->json([
            'success' => true,
            'message' => 'Status toggled successfully.',
            'status' => $category->status,
        ]);
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore(int $id): RedirectResponse
    {
        $this->categoryService->restore($id);
        return redirect()->route('admin.contacts.categories.index')
            ->with('success', 'Category restored successfully.');
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $count = $this->categoryService->bulkDelete($ids);
        return response()->json([
            'success' => true,
            'message' => "{$count} categories deleted successfully.",
        ]);
    }

    /**
     * Bulk update status of categories.
     */
    public function bulkStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        $status = $request->boolean('status');
        $count = $this->categoryService->bulkStatus($ids, $status);
        return response()->json([
            'success' => true,
            'message' => "{$count} categories status updated successfully.",
        ]);
    }
}
