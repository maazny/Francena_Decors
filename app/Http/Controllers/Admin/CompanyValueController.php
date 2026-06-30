<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyValueRequest;
use App\Models\AboutSection;
use App\Models\CompanyValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyValueController extends Controller
{
    public function create(): View
    {
        $companyValue = new CompanyValue([
            'display_order' => CompanyValue::max('display_order') + 1,
            'status' => true,
        ]);

        return view('admin.company-values.create', compact('companyValue'));
    }

    public function store(CompanyValueRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            CompanyValue::create($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Company value created successfully.');
    }

    public function edit(CompanyValue $companyValue): View
    {
        return view('admin.company-values.edit', compact('companyValue'));
    }

    public function update(CompanyValueRequest $request, CompanyValue $companyValue): RedirectResponse
    {
        DB::transaction(function () use ($request, $companyValue): void {
            $companyValue->update($this->payload($request));
        });

        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Company value updated successfully.');
    }

    public function destroy(CompanyValue $companyValue): RedirectResponse
    {
        $companyValue->delete();
        AboutSection::clearCache();

        return redirect()->route('admin.about-sections.edit')->with('success', 'Company value deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(CompanyValueRequest $request): array
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        return $data;
    }
}
