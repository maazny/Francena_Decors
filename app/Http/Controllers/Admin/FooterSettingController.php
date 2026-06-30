<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterSettingRequest;
use App\Models\FooterSetting;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FooterSettingController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.footer.settings.edit');
    }

    public function edit(): View
    {
        $footerSetting = FooterSetting::with([
            'logo',
            'background',
            'columns.links',
            'socialLinks',
            'businessHours',
            'widgets',
        ])->firstOrCreate([]);
        $mediaOptions = Media::where('is_image', true)->where('status', true)->latest()->get();

        return view('admin.footer-settings.edit', compact('footerSetting', 'mediaOptions'));
    }

    public function update(FooterSettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($request, $data): void {
            $footerSetting = FooterSetting::firstOrCreate([]);
            $footerSetting->update($this->settingsPayload($request, $data));

            $footerSetting->columns()->delete();
            foreach ($data['columns'] ?? [] as $columnIndex => $columnData) {
                if (! filled($columnData['title'] ?? null)) {
                    continue;
                }

                $column = $footerSetting->columns()->create([
                    'title' => $columnData['title'],
                    'type' => $columnData['type'] ?? 'links',
                    'sort_order' => $columnData['sort_order'] ?? $columnIndex,
                    'status' => $request->boolean("columns.{$columnIndex}.status"),
                ]);

                foreach ($columnData['links'] ?? [] as $linkIndex => $linkData) {
                    if (! filled($linkData['label'] ?? null)) {
                        continue;
                    }

                    $column->links()->create([
                        'label' => $linkData['label'],
                        'url' => $linkData['url'] ?? '#',
                        'target' => $linkData['target'] ?? '_self',
                        'icon' => $linkData['icon'] ?? null,
                        'sort_order' => $linkData['sort_order'] ?? $linkIndex,
                        'status' => $request->boolean("columns.{$columnIndex}.links.{$linkIndex}.status"),
                    ]);
                }
            }

            $footerSetting->socialLinks()->delete();
            foreach ($data['social_links'] ?? [] as $index => $socialData) {
                if (! filled($socialData['platform'] ?? null) || ! filled($socialData['url'] ?? null)) {
                    continue;
                }

                $footerSetting->socialLinks()->create([
                    'platform' => $socialData['platform'],
                    'url' => $socialData['url'],
                    'icon' => $socialData['icon'] ?? null,
                    'sort_order' => $socialData['sort_order'] ?? $index,
                    'status' => $request->boolean("social_links.{$index}.status"),
                ]);
            }

            $footerSetting->businessHours()->delete();
            foreach ($data['business_hours'] ?? [] as $index => $hourData) {
                if (! filled($hourData['day_label'] ?? null) || ! filled($hourData['time_label'] ?? null)) {
                    continue;
                }

                $footerSetting->businessHours()->create([
                    'day_label' => $hourData['day_label'],
                    'time_label' => $hourData['time_label'],
                    'sort_order' => $hourData['sort_order'] ?? $index,
                    'status' => $request->boolean("business_hours.{$index}.status"),
                ]);
            }

            $footerSetting->widgets()->delete();
            foreach ($data['widgets'] ?? [] as $index => $widgetData) {
                if (! filled($widgetData['title'] ?? null)) {
                    continue;
                }

                $footerSetting->widgets()->create([
                    'title' => $widgetData['title'],
                    'content' => $widgetData['content'] ?? null,
                    'icon' => $widgetData['icon'] ?? null,
                    'sort_order' => $widgetData['sort_order'] ?? $index,
                    'status' => $request->boolean("widgets.{$index}.status"),
                ]);
            }
        });

        FooterSetting::clearCache();

        return redirect()->route('admin.footer.settings.edit')->with('success', 'Footer settings updated successfully.');
    }

    /**
     * Build the footer settings payload from validated input.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function settingsPayload(FooterSettingRequest $request, array $data): array
    {
        $payload = Arr::only($data, [
            'layout',
            'logo_media_id',
            'background_media_id',
            'company_description',
            'newsletter_title',
            'newsletter_description',
            'newsletter_placeholder',
            'newsletter_button_text',
            'contact_heading',
            'contact_address',
            'contact_phone',
            'contact_email',
            'business_hours_heading',
            'copyright_text',
            'bottom_bar_text',
            'background_color',
            'text_color',
            'heading_color',
            'link_color',
            'bottom_background_color',
        ]);

        foreach ([
            'show_logo',
            'show_description',
            'show_columns',
            'show_contact',
            'show_business_hours',
            'show_social_links',
            'show_widgets',
            'newsletter_enabled',
            'bottom_bar_enabled',
            'status',
        ] as $field) {
            $payload[$field] = $request->boolean($field);
        }

        foreach ($payload as $field => $value) {
            if ($value === '') {
                $payload[$field] = null;
            }
        }

        return $payload;
    }
}
