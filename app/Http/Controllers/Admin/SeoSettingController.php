<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSeoSettingRequest;
use App\Services\SeoSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeoSettingController extends Controller
{
    protected SeoSettingService $settingService;

    public function __construct(SeoSettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Show global settings configuration dashboard.
     */
    public function edit(): View
    {
        $settings = $this->settingService->loadGlobalSettings();
        return view('admin.seo.settings', compact('settings'));
    }

    /**
     * Update global default properties.
     */
    public function update(UpdateSeoSettingRequest $request): RedirectResponse
    {
        $this->settingService->saveGlobalSettings($request->validated());

        return redirect()->back()->with('success', 'Global default SEO settings updated successfully.');
    }
}
