<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhyChooseUsRequest;
use App\Models\AboutSection;
use App\Models\WhyChooseUs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WhyChooseUsController extends Controller
{
    public function create(): View
    {
        $whyChooseUs = new WhyChooseUs([
            'display_order' => WhyChooseUs::max('display_order') + 1,
            'status' => true,
        ]);

        return view('admin.why-choose-us.create', compact('whyChooseUs'));
    }

    public function store(WhyChooseUsRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            WhyChooseUs::create($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Why Choose Us item created successfully.');
    }

    public function edit(WhyChooseUs $whyChooseU): View
    {
        return view('admin.why-choose-us.edit', ['whyChooseUs' => $whyChooseU]);
    }

    public function update(WhyChooseUsRequest $request, WhyChooseUs $whyChooseU): RedirectResponse
    {
        DB::transaction(function () use ($request, $whyChooseU): void {
            $whyChooseU->update($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Why Choose Us item updated successfully.');
    }

    public function destroy(WhyChooseUs $whyChooseU): RedirectResponse
    {
        $whyChooseU->delete();
        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Why Choose Us item deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(WhyChooseUsRequest $request): array
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
