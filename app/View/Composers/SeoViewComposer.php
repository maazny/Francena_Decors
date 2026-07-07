<?php

namespace App\View\Composers;

use App\Models\SeoSetting;
use App\Models\SeoPage;
use App\Services\SeoStructuredDataService;
use Illuminate\View\View;

class SeoViewComposer
{
    protected SeoStructuredDataService $schemaService;

    public function __construct(SeoStructuredDataService $schemaService)
    {
        $this->schemaService = $schemaService;
    }

    public function compose(View $view): void
    {
        // Suppress errors during migration or schema boot
        try {
            $settings = SeoSetting::getCached();
        } catch (\Throwable $e) {
            $settings = new SeoSetting();
        }

        $path = '/' . trim(request()->path(), '/');
        $page = null;

        // 1. Resolve from Route Model Binding parameters
        try {
            $route = request()->route();
            if ($route) {
                foreach ($route->parameters() as $parameter) {
                    if (is_object($parameter) && method_exists($parameter, 'seoPage')) {
                        $page = $parameter->seoPage;
                        if ($page) {
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 2. Resolve from exact slug path match
        try {
            if (!$page) {
                $page = SeoPage::where('slug', $path)->first();
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 3. Compile metadata parameters
        $ogImageFallback = 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1800&q=80';
        
        $seo = [
            'site_name' => $settings->site_name ?? 'Fancy Decorators',
            'title' => $page->title ?? $settings->meta_title ?? 'Fancy Decorators | Luxury Construction Company',
            'meta_description' => $page->meta_description ?? $settings->meta_description ?? 'Premium residential & commercial building renovations.',
            'meta_keywords' => $page->meta_keywords ?? $settings->meta_keywords ?? 'builder, renovation, architecture',
            'robots' => $page->robots ?? $settings->robots ?? 'index, follow',
            'canonical_url' => $page->canonical_url ?? ($settings->canonical_url ? url($settings->canonical_url) : request()->url()),
            'og_title' => $page->og_title ?? $page->title ?? $settings->og_title ?? $settings->meta_title ?? 'Fancy Decorators',
            'og_description' => $page->og_description ?? $page->meta_description ?? $settings->og_description ?? $settings->meta_description ?? 'Renovations.',
            'og_image' => $page->og_image ?? $settings->og_image ?? $ogImageFallback,
            'og_type' => $settings->og_type ?? 'website',
            'twitter_card' => $page->twitter_card ?? $settings->twitter_card ?? 'summary_large_image',
            'theme_color' => $settings->theme_color ?? '#d4af5f',
            'custom_head_scripts' => ($settings->custom_head_scripts ?? '') . "\n" . ($page->custom_head_scripts ?? ''),
            'custom_footer_scripts' => ($settings->custom_footer_scripts ?? '') . "\n" . ($page->custom_footer_scripts ?? ''),
            'structured_data' => $page ? $this->schemaService->generateJsonLdMarkup($page) : '',
        ];

        $view->with('seo', (object) $seo);
    }
}
