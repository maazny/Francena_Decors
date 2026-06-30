<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutSectionRequest;
use App\Models\AboutSection;
use App\Models\CompanyTimeline;
use App\Models\CompanyValue;
use App\Models\Media;
use App\Models\WhyChooseUs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AboutSectionController extends Controller
{
    public function edit(): View
    {
        return view('admin.about-sections.edit', [
            'aboutSection' => AboutSection::with(['chairmanImage', 'companyVideo', 'brochureFile', 'ogImage'])->firstOrCreate([]),
            'companyValues' => CompanyValue::ordered()->paginate(10, ['*'], 'values_page'),
            'companyTimelines' => CompanyTimeline::with('image')->ordered()->paginate(10, ['*'], 'timelines_page'),
            'whyChooseUsItems' => WhyChooseUs::ordered()->paginate(10, ['*'], 'why_page'),
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
            'videoOptions' => Media::where('is_image', false)->where('status', true)->where('mime_type', 'like', 'video/%')->latest()->get(),
            'fileOptions' => Media::where('is_image', false)->where('status', true)->latest()->get(),
        ]);
    }

    public function update(AboutSectionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $payload = $request->validated();

            foreach ($payload as $field => $value) {
                if ($value === '') {
                    $payload[$field] = null;
                }
            }

            $payload['status'] = $request->boolean('status');

            AboutSection::firstOrCreate([])->update($payload);
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'About section updated successfully.');
    }
}
