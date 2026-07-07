<?php

namespace App\Services;

use App\Models\SeoStructuredData;
use App\Models\SeoPage;
use Illuminate\Support\Facades\Cache;

class SeoStructuredDataService
{
    /**
     * Validate raw JSON-LD markup.
     */
    public function validateJson(string $json): bool
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Create structured data.
     */
    public function createStructuredData(array $data): SeoStructuredData
    {
        $schema = SeoStructuredData::create($data);
        $this->clearPageCache($schema->seo_page_id);
        return $schema;
    }

    /**
     * Update structured data.
     */
    public function updateStructuredData(SeoStructuredData $schema, array $data): bool
    {
        $schema->update($data);
        $this->clearPageCache($schema->seo_page_id);
        return true;
    }

    /**
     * Delete structured data.
     */
    public function deleteStructuredData(SeoStructuredData $schema): bool
    {
        $pageId = $schema->seo_page_id;
        $schema->delete();
        $this->clearPageCache($pageId);
        return true;
    }

    /**
     * Compile page schema markup.
     */
    public function generateJsonLdMarkup(SeoPage $page): string
    {
        return Cache::rememberForever('seo_json_ld_page_' . $page->id, function () use ($page) {
            $markup = [];
            
            // Get active schemas
            $schemas = $page->structuredData()->active()->get();

            foreach ($schemas as $schema) {
                if ($schema->custom_json_ld) {
                    $markup[] = "<script type=\"application/ld+json\">\n" . trim($schema->custom_json_ld) . "\n</script>";
                } elseif ($schema->schema_data) {
                    $json = json_encode($schema->schema_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    $markup[] = "<script type=\"application/ld+json\">\n" . $json . "\n</script>";
                }
            }

            return implode("\n", $markup);
        });
    }

    /**
     * Clear caches associated with the page structure.
     */
    protected function clearPageCache(?int $pageId): void
    {
        if ($pageId) {
            Cache::forget('seo_json_ld_page_' . $pageId);
            $page = SeoPage::find($pageId);
            if ($page) {
                Cache::forget('seo_page_slug_' . md5($page->slug));
                if ($page->page_key) {
                    Cache::forget('seo_page_key_' . $page->page_key);
                }
            }
        }
    }
}
