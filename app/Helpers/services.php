<?php

use App\Services\ServiceCatalogService;
use Illuminate\Support\Facades\Schema;

if (! function_exists('featured_services')) {
    function featured_services()
    {
        if (! Schema::hasTable('services')) {
            return collect();
        }

        return app(ServiceCatalogService::class)->featuredServices();
    }
}

if (! function_exists('service_categories')) {
    function service_categories()
    {
        if (! Schema::hasTable('service_categories')) {
            return collect();
        }

        return app(ServiceCatalogService::class)->activeCategories();
    }
}
