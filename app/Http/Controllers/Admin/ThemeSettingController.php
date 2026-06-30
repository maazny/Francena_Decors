<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeSettingRequest;
use App\Models\ThemeSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThemeSettingController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.theme-settings.edit');
    }

    public function edit(): View
    {
        $themeSetting = ThemeSetting::firstOrCreate([]);

        return view('admin.theme-settings.edit', compact('themeSetting'));
    }

    public function update(ThemeSettingRequest $request): RedirectResponse
    {
        $themeSetting = ThemeSetting::firstOrCreate([]);
        $data = $request->validated();

        $data['loader_enabled'] = $request->boolean('loader_enabled');
        $data['dark_mode'] = $request->boolean('dark_mode');
        $data['animation_enabled'] = $request->boolean('animation_enabled');
        $data['status'] = $request->boolean('status');

        $themeSetting->update($data);
        ThemeSetting::clearCache();

        return redirect()->route('admin.theme-settings.edit')->with('success', 'Theme settings updated successfully.');
    }
}
