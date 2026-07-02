<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreServiceFaqRequest;
use App\Models\Service;
use App\Models\ServiceFaq;
use App\Services\ServiceCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ServiceFaqController extends Controller
{
    public function store(StoreServiceFaqRequest $request, Service $service): RedirectResponse
    {
        DB::transaction(function () use ($request, $service): void {
            $service->faqs()->create($request->validated());
        });

        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.edit', $service)->with('success', 'FAQ item added successfully.');
    }

    public function update(StoreServiceFaqRequest $request, ServiceFaq $serviceFaq): RedirectResponse
    {
        DB::transaction(function () use ($request, $serviceFaq): void {
            $serviceFaq->update($request->validated());
        });

        ServiceCatalogService::clearCache(service: $serviceFaq->service, category: $serviceFaq->service->category);

        return redirect()->route('admin.services.edit', $serviceFaq->service)->with('success', 'FAQ item updated successfully.');
    }

    public function destroy(ServiceFaq $serviceFaq): RedirectResponse
    {
        $service = $serviceFaq->service;
        $serviceFaq->delete();

        ServiceCatalogService::clearCache(service: $service, category: $service->category);

        return redirect()->route('admin.services.edit', $service)->with('success', 'FAQ item removed successfully.');
    }

    public function toggleStatus(ServiceFaq $serviceFaq): RedirectResponse
    {
        $serviceFaq->update(['status' => ! $serviceFaq->status]);

        ServiceCatalogService::clearCache(service: $serviceFaq->service, category: $serviceFaq->service->category);

        return redirect()->route('admin.services.edit', $serviceFaq->service)->with('success', 'FAQ status updated successfully.');
    }
}
