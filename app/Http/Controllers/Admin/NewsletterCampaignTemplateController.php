<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Models\NewsletterCampaignTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterCampaignTemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index(Request $request): View
    {
        $query = NewsletterCampaignTemplate::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('subject', 'like', "%{$search}%");
        }

        $templates = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.newsletter.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create(): View
    {
        return view('admin.newsletter.templates.create');
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(StoreTemplateRequest $request): RedirectResponse
    {
        NewsletterCampaignTemplate::create($request->validated());

        return redirect()->route('admin.newsletter.templates.index')
            ->with('success', 'Template created successfully.');
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(NewsletterCampaignTemplate $template): View
    {
        return view('admin.newsletter.templates.edit', compact('template'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(UpdateTemplateRequest $request, NewsletterCampaignTemplate $template): RedirectResponse
    {
        $template->update($request->validated());

        return redirect()->route('admin.newsletter.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(NewsletterCampaignTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('admin.newsletter.templates.index')
            ->with('success', 'Template deleted successfully.');
    }

    /**
     * Bulk delete templates.
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            NewsletterCampaignTemplate::whereIn('id', $ids)->delete();
            return redirect()->route('admin.newsletter.templates.index')
                ->with('success', 'Selected templates deleted successfully.');
        }

        return redirect()->route('admin.newsletter.templates.index')
            ->with('error', 'No templates selected.');
    }
}
