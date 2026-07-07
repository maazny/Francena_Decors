<?php

namespace App\Http\Controllers;

use App\Services\SeoSitemapService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    protected SeoSitemapService $sitemapService;

    public function __construct(SeoSitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Display centralized dynamic XML sitemap.
     */
    public function index(): Response
    {
        $xml = Cache::remember('seo_sitemap_xml', 86400, function () {
            return $this->sitemapService->generateXml();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
