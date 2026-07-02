<?php

namespace App\Http\Controllers;

use App\Models\ClientBrand;
use App\Services\ClientBrandService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientsBrandsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $category = $request->query('category');

        $brands = ClientBrandService::buildQuery([
            'search' => $search,
            'category' => $category,
        ])->paginate(12)->withQueryString();

        return view('clients-brands.index', [
            'brands' => $brands,
            'search' => $search,
            'category' => $category,
            'categories' => ClientBrandService::getCategories(),
        ]);
    }

    public function show(ClientBrand $clientBrand): View
    {
        abort_unless($clientBrand->status === 'published', 404);

        return view('clients-brands.show', [
            'brand' => $clientBrand->load('logo'),
        ]);
    }
}
