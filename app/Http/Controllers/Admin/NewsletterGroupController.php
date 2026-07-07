<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\NewsletterGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class NewsletterGroupController extends Controller
{
    /**
     * Display a listing of the groups.
     */
    public function index(Request $request): View
    {
        $query = NewsletterGroup::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $groups = $query->orderBy('display_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.newsletter.groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create(): View
    {
        return view('admin.newsletter.groups.create');
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(StoreGroupRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);
        $data['is_dynamic'] = $request->boolean('is_dynamic', false);

        NewsletterGroup::create($data);

        return redirect()->route('admin.newsletter.groups.index')
            ->with('success', 'Group created successfully.');
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(NewsletterGroup $group): View
    {
        return view('admin.newsletter.groups.edit', compact('group'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(UpdateGroupRequest $request, NewsletterGroup $group): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['name']);
        $data['status'] = $request->boolean('status', true);
        $data['is_dynamic'] = $request->boolean('is_dynamic', false);

        $group->update($data);

        return redirect()->route('admin.newsletter.groups.index')
            ->with('success', 'Group updated successfully.');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(NewsletterGroup $group): RedirectResponse
    {
        $group->subscribers()->detach();
        $group->delete();

        return redirect()->route('admin.newsletter.groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    /**
     * Toggle the active status of the group.
     */
    public function toggleStatus(NewsletterGroup $group): RedirectResponse
    {
        $group->update(['status' => !$group->status]);

        return redirect()->route('admin.newsletter.groups.index')
            ->with('success', 'Group status updated successfully.');
    }

    /**
     * Bulk delete groups.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            // Detach subscribers first
            foreach (NewsletterGroup::whereIn('id', $ids)->get() as $group) {
                $group->subscribers()->detach();
                $group->delete();
            }
            return redirect()->route('admin.newsletter.groups.index')
                ->with('success', 'Selected groups deleted successfully.');
        }

        return redirect()->route('admin.newsletter.groups.index')
            ->with('error', 'No groups selected.');
    }
}
