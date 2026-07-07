<?php

namespace App\Services;

use App\Models\SeoSitemap;
use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use App\Events\SitemapGenerated;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SeoSitemapService
{
    /**
     * Generate sitemap XML content dynamically.
     */
    public function generateXml(): string
    {
        $urls = [];

        // 1. Add static configured sitemap entries from DB
        $sitemaps = SeoSitemap::active()->get();
        foreach ($sitemaps as $sitemap) {
            $urls[] = [
                'loc' => url($sitemap->loc),
                'lastmod' => $sitemap->lastmod ? $sitemap->lastmod->toIso8601String() : Carbon::now()->toIso8601String(),
                'changefreq' => $sitemap->changefreq,
                'priority' => $sitemap->priority,
            ];
        }

        // Add core defaults if sitemaps table is empty
        if (empty($urls)) {
            $corePaths = [
                '/' => 1.0,
                '/about' => 0.8,
                '/services' => 0.8,
                '/projects' => 0.8,
                '/gallery' => 0.7,
                '/faq' => 0.6,
                '/blog' => 0.8,
                '/careers' => 0.7,
                '/contact' => 0.9,
            ];
            foreach ($corePaths as $path => $priority) {
                $urls[] = [
                    'loc' => url($path),
                    'lastmod' => Carbon::now()->toIso8601String(),
                    'changefreq' => 'weekly',
                    'priority' => $priority,
                ];
            }
        }

        // 2. Add Blog Posts dynamically
        try {
            if (class_exists(BlogPost::class)) {
                $posts = BlogPost::where('status', 'published')->get();
                foreach ($posts as $post) {
                    $urls[] = [
                        'loc' => url('/blog/' . $post->slug),
                        'lastmod' => $post->updated_at ? $post->updated_at->toIso8601String() : Carbon::now()->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => 0.6,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Sitemap BlogPost fetch failed: " . $e->getMessage());
        }

        // 3. Add Projects dynamically
        try {
            if (class_exists(Project::class)) {
                $projects = Project::all();
                foreach ($projects as $project) {
                    $urls[] = [
                        'loc' => url('/projects/' . $project->slug),
                        'lastmod' => $project->updated_at ? $project->updated_at->toIso8601String() : Carbon::now()->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => 0.7,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Sitemap Project fetch failed: " . $e->getMessage());
        }

        // 4. Add Services dynamically
        try {
            if (class_exists(Service::class)) {
                $services = Service::all();
                foreach ($services as $service) {
                    $urls[] = [
                        'loc' => url('/services/' . $service->slug),
                        'lastmod' => $service->updated_at ? $service->updated_at->toIso8601String() : Carbon::now()->toIso8601String(),
                        'changefreq' => 'weekly',
                        'priority' => 0.7,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Sitemap Service fetch failed: " . $e->getMessage());
        }

        // Compile XML string
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "        <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "        <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "        <priority>" . number_format($url['priority'], 1) . "</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Generate sitemap and cache it.
     */
    public function cacheXmlSitemap(): string
    {
        $xml = $this->generateXml();
        
        Cache::forever('seo_sitemap_xml', $xml);

        // Save to public root directly for static file serving by webserver
        try {
            file_put_contents(public_path('sitemap.xml'), $xml);
        } catch (\Exception $e) {
            Log::error("Failed to write sitemap.xml to public path: " . $e->getMessage());
        }

        event(new SitemapGenerated(url('/sitemap.xml')));

        return $xml;
    }

    /**
     * Refresh sitemap cache.
     */
    public function refreshXmlSitemap(): void
    {
        $this->cacheXmlSitemap();
    }
}
