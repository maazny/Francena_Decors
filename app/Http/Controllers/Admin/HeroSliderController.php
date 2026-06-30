<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSliderStoreRequest;
use App\Http\Requests\HeroSliderUpdateRequest;
use App\Models\HeroSlider;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HeroSliderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $sort = $request->query('sort', 'display_order');
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';

        $allowedSorts = ['title', 'display_order', 'status', 'start_date', 'end_date', 'created_at'];
        $sort = in_array($sort, $allowedSorts, true) ? $sort : 'display_order';

        $heroSliders = HeroSlider::query()
            ->with(['desktopImage', 'mobileImage', 'backgroundVideo'])
            ->withTrashed()
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('title', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('badge_text', 'like', "%{$search}%");
                });
            })
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', (bool) $status))
            ->orderBy($sort, $direction)
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.hero-sliders.index', compact('heroSliders', 'search', 'status', 'sort', 'direction'));
    }

    public function create(): View
    {
        $heroSlider = new HeroSlider([
            'overlay_color' => '#000000',
            'overlay_opacity' => 65,
            'text_alignment' => 'center',
            'content_position' => 'center',
            'button_one_target' => '_self',
            'button_two_target' => '_self',
            'badge_color' => '#d4af5f',
            'enable_animation' => true,
            'animation_type' => 'fade-up',
            'animation_duration' => 900,
            'display_order' => HeroSlider::withTrashed()->max('display_order') + 1,
            'status' => true,
        ]);

        return view('admin.hero-sliders.create', $this->formData($heroSlider));
    }

    public function store(HeroSliderStoreRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            HeroSlider::create($this->payload($request));
        });

        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide created successfully.');
    }

    public function edit(HeroSlider $heroSlider): View
    {
        return view('admin.hero-sliders.edit', $this->formData($heroSlider->load(['desktopImage', 'mobileImage', 'backgroundVideo'])));
    }

    public function update(HeroSliderUpdateRequest $request, HeroSlider $heroSlider): RedirectResponse
    {
        DB::transaction(function () use ($request, $heroSlider): void {
            $heroSlider->update($this->payload($request));
        });

        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.edit', $heroSlider)->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlider $heroSlider): RedirectResponse
    {
        $heroSlider->delete();
        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide deleted successfully.');
    }

    public function restore(int $heroSlider): RedirectResponse
    {
        $slide = HeroSlider::withTrashed()->findOrFail($heroSlider);
        $slide->restore();
        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide restored successfully.');
    }

    public function toggleStatus(HeroSlider $heroSlider): RedirectResponse
    {
        $heroSlider->update(['status' => ! $heroSlider->status]);
        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide status updated successfully.');
    }

    public function duplicate(HeroSlider $heroSlider): RedirectResponse
    {
        DB::transaction(function () use ($heroSlider): void {
            $copy = $heroSlider->replicate();
            $copy->title = $heroSlider->title.' Copy';
            $copy->status = false;
            $copy->display_order = HeroSlider::withTrashed()->max('display_order') + 1;
            $copy->save();
        });

        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide duplicated successfully.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:hero_sliders,id'],
            'orders.*.display_order' => ['required', 'integer', 'between:0,9999'],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['orders'] as $order) {
                HeroSlider::whereKey($order['id'])->update(['display_order' => $order['display_order']]);
            }
        });

        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero slide ordering updated successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,deactivate,delete,restore'],
            'selected' => ['required', 'array'],
            'selected.*' => ['integer'],
        ]);

        DB::transaction(function () use ($data): void {
            $query = HeroSlider::withTrashed()->whereIn('id', $data['selected']);

            match ($data['action']) {
                'activate' => $query->update(['status' => true]),
                'deactivate' => $query->update(['status' => false]),
                'delete' => $query->get()->each->delete(),
                'restore' => $query->get()->each->restore(),
            };
        });

        HeroSlider::clearCache();

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Bulk action completed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(HeroSlider $heroSlider): array
    {
        return [
            'heroSlider' => $heroSlider,
            'imageOptions' => Media::where('is_image', true)->where('status', true)->latest()->get(),
            'videoOptions' => Media::where('is_image', false)
                ->where('status', true)
                ->where('mime_type', 'like', 'video/%')
                ->latest()
                ->get(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(HeroSliderStoreRequest $request): array
    {
        $payload = Arr::only($request->validated(), [
            'title',
            'subtitle',
            'description',
            'desktop_image_id',
            'mobile_image_id',
            'background_video_id',
            'overlay_color',
            'overlay_opacity',
            'text_alignment',
            'content_position',
            'button_one_text',
            'button_one_url',
            'button_one_target',
            'button_two_text',
            'button_two_url',
            'button_two_target',
            'badge_text',
            'badge_color',
            'animation_type',
            'animation_duration',
            'display_order',
            'start_date',
            'end_date',
        ]);

        $payload['enable_animation'] = $request->boolean('enable_animation');
        $payload['status'] = $request->boolean('status');

        foreach ($payload as $field => $value) {
            if ($value === '') {
                $payload[$field] = null;
            }
        }

        return $payload;
    }
}
