<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HeroSliderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'subtitle' => ['nullable', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:1000'],
            'desktop_image_id' => ['required_without:background_video_id', 'nullable', 'exists:media,id'],
            'mobile_image_id' => ['nullable', 'exists:media,id'],
            'background_video_id' => ['nullable', 'exists:media,id'],
            'overlay_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'overlay_opacity' => ['required', 'integer', 'between:0,100'],
            'text_alignment' => ['required', Rule::in(['start', 'center', 'end'])],
            'content_position' => ['required', Rule::in(['left', 'center', 'right'])],
            'button_one_text' => ['nullable', 'string', 'max:100'],
            'button_one_url' => ['nullable', 'string', 'max:500'],
            'button_one_target' => ['required', Rule::in(['_self', '_blank'])],
            'button_two_text' => ['nullable', 'string', 'max:100'],
            'button_two_url' => ['nullable', 'string', 'max:500'],
            'button_two_target' => ['required', Rule::in(['_self', '_blank'])],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'badge_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'enable_animation' => ['sometimes', 'boolean'],
            'animation_type' => ['required', Rule::in(['fade-up', 'fade-down', 'zoom-in', 'slide-left', 'slide-right'])],
            'animation_duration' => ['required', 'integer', 'between:100,5000'],
            'display_order' => ['required', 'integer', 'between:0,9999'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
