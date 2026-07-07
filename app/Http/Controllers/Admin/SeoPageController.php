<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeoPageRequest;
use App\Http\Requests\UpdateSeoPageRequest;
use App\Models\SeoPage;
use App\Services\SeoPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoPageController extends Controller
{
    protected SeoPageService $pageService;

    public function __construct(SeoPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display page overrides index list.
     */
    public function index(): View
    {
        $pages = SeoPage::with('structuredData')->paginate(20);
        return view('admin.seo.pages.index', compact('pages'));
    }

    /**
     * Show page creation override wizard.
     */
    public function create(): View
    {
        return view('admin.seo.pages.create');
    }

    /**
     * Store new override.
     */
    public function store(StoreSeoPageRequest $request): RedirectResponse
    {
        $page = $this->pageService->createSeoPage($request->validated());

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'Page SEO overrides configured successfully.');
    }

    /**
     * Show page editor.
     */
    public function edit(SeoPage $page): View
    {
        return view('admin.seo.pages.edit', compact('page'));
    }

    /**
     * Update metadata.
     */
    public function update(UpdateSeoPageRequest $request, SeoPage $page): RedirectResponse
    {
        $this->pageService->updateSeoPage($page, $request->validated());

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'Page SEO properties updated successfully.');
    }

    /**
     * Delete overrides.
     */
    public function destroy(SeoPage $page): RedirectResponse
    {
        $this->pageService->deleteSeoPage($page);

        return redirect()->route('admin.seo.pages.index')
            ->with('success', 'Page SEO configuration deleted successfully.');
    }

    /**
     * Clone global properties directly.
     */
    public function clone(SeoPage $page): RedirectResponse
    {
        $this->pageService->cloneDefaultsToPage($page);

        return redirect()->back()->with('success', 'Default global properties cloned successfully.');
    }
}
