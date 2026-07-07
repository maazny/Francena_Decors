<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStructuredDataRequest;
use App\Http\Requests\UpdateStructuredDataRequest;
use App\Models\SeoStructuredData;
use App\Models\SeoPage;
use App\Services\SeoStructuredDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoStructuredDataController extends Controller
{
    protected SeoStructuredDataService $structuredDataService;

    public function __construct(SeoStructuredDataService $structuredDataService)
    {
        $this->structuredDataService = $structuredDataService;
    }

    /**
     * Display structured JSON-LD data list.
     */
    public function index(): View
    {
        $schemas = SeoStructuredData::with('page')->paginate(20);
        return view('admin.seo.schemas.index', compact('schemas'));
    }

    /**
     * Show structured schema template config form.
     */
    public function create(): View
    {
        $pages = SeoPage::orderBy('slug', 'asc')->get();
        return view('admin.seo.schemas.create', compact('pages'));
    }

    /**
     * Store structured schemas.
     */
    public function store(StoreStructuredDataRequest $request): RedirectResponse
    {
        $this->structuredDataService->createStructuredData($request->validated());

        return redirect()->route('admin.seo.structured-data.index')
            ->with('success', 'Structured data JSON-LD schema added successfully.');
    }

    /**
     * Show structured schema configurations editor.
     */
    public function edit(SeoStructuredData $structuredDatum): View
    {
        // Parameter matching resource binding: structuredDatum
        $pages = SeoPage::orderBy('slug', 'asc')->get();
        return view('admin.seo.schemas.edit', [
            'schema' => $structuredDatum,
            'pages' => $pages,
        ]);
    }

    /**
     * Update structured data schema.
     */
    public function update(UpdateStructuredDataRequest $request, SeoStructuredData $structuredDatum): RedirectResponse
    {
        $this->structuredDataService->updateStructuredData($structuredDatum, $request->validated());

        return redirect()->route('admin.seo.structured-data.index')
            ->with('success', 'Structured data JSON-LD schema updated successfully.');
    }

    /**
     * Delete structured schemas.
     */
    public function destroy(SeoStructuredData $structuredDatum): RedirectResponse
    {
        $this->structuredDataService->deleteStructuredData($structuredDatum);

        return redirect()->route('admin.seo.structured-data.index')
            ->with('success', 'Structured data JSON-LD schema deleted successfully.');
    }
}
