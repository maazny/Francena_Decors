<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSitemap;
use App\Services\SeoSitemapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoSitemapController extends Controller
{
    protected SeoSitemapService $sitemapService;

    public function __construct(SeoSitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Display sitemaps panel.
     */
    public function index(): View
    {
        $sitemaps = SeoSitemap::all();
        return view('admin.seo.sitemaps.index', compact('sitemaps'));
    }

    /**
     * Trigger manual sitemap generation.
     */
    public function generate(): RedirectResponse
    {
        try {
            $this->sitemapService->refreshXmlSitemap();
            return redirect()->back()->with('success', 'Sitemap XML generated and published to public root folder successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate sitemap: ' . $e->getMessage());
        }
    }
}
