<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.site-settings.edit');
    }

    public function edit(): View
    {
        $siteSetting = SiteSetting::firstOrCreate([]);

        return view('admin.site-settings.edit', compact('siteSetting'));
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        $siteSetting = SiteSetting::firstOrCreate([]);
        $data = $request->validated();

        $data['maintenance_mode'] = $request->boolean('maintenance_mode');
        $data['status'] = $request->boolean('status');

        foreach (['logo', 'footer_logo', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $siteSetting->deleteImage($field);
                $data[$field] = $request->file($field)->store('site-settings', 'public');
            }
        }

        $siteSetting->update($data);

        return redirect()->route('admin.site-settings.edit')->with('success', 'Site settings were updated successfully.');
    }
}
