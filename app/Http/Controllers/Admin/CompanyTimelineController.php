<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyTimelineRequest;
use App\Models\AboutSection;
use App\Models\CompanyTimeline;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyTimelineController extends Controller
{
    public function create(): View
    {
        return view('admin.company-timelines.create', [
            'companyTimeline' => new CompanyTimeline([
                'display_order' => CompanyTimeline::max('display_order') + 1,
                'status' => true,
            ]),
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function store(CompanyTimelineRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            CompanyTimeline::create($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Timeline item created successfully.');
    }

    public function edit(CompanyTimeline $companyTimeline): View
    {
        return view('admin.company-timelines.edit', [
            'companyTimeline' => $companyTimeline->load('image'),
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
        ]);
    }

    public function update(CompanyTimelineRequest $request, CompanyTimeline $companyTimeline): RedirectResponse
    {
        DB::transaction(function () use ($request, $companyTimeline): void {
            $companyTimeline->update($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Timeline item updated successfully.');
    }

    public function destroy(CompanyTimeline $companyTimeline): RedirectResponse
    {
        $companyTimeline->delete();
        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Timeline item deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(CompanyTimelineRequest $request): array
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
