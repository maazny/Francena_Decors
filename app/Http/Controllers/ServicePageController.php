<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\ServiceCatalogService;
use Illuminate\View\View;

class ServicePageController extends Controller
{
    public function index(ServiceCatalogService $catalog): View
    {
        $services = Service::with(['category', 'featuredImage'])
            ->active()
            ->ordered()
            ->paginate(12);

        return view('services.index', [
            'services' => $services,
            'categories' => $catalog->activeCategories(),
        ]);
    }

    public function show(string $slug, ServiceCatalogService $catalog): View
    {
        $service = $catalog->serviceBySlug($slug);
        $relatedServices = Service::with('featuredImage')
            ->active()
            ->where('category_id', $service->category_id)
            ->whereKeyNot($service->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }

    public function category(string $slug, ServiceCatalogService $catalog): View
    {
        $category = $catalog->categoryBySlug($slug);

        return view('services.category', compact('category'));
    }
}
