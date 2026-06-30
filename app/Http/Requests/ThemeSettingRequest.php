<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeSettingRequest extends FormRequest
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
            'primary_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'surface_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'heading_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'link_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'link_hover_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_hover_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_hover_text' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'navbar_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'navbar_text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'footer_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'footer_text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_border_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'input_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'input_border_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'success_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'warning_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'danger_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'info_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'font_family' => ['nullable', 'string', 'max:100'],
            'heading_font' => ['nullable', 'string', 'max:100'],
            'base_font_size' => ['nullable', 'integer', 'between:10,32'],
            'border_radius' => ['nullable', 'string', 'max:50'],
            'box_shadow' => ['nullable', 'string', 'max:255'],
            'container_width' => ['nullable', 'integer', 'between:800,2000'],
            'loader_enabled' => ['sometimes', 'boolean'],
            'loader_style' => ['nullable', 'string', 'max:100'],
            'loader_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'loader_background' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'dark_mode' => ['sometimes', 'boolean'],
            'animation_enabled' => ['sometimes', 'boolean'],
            'animation_speed' => ['nullable', 'integer', 'between:0,2000'],
            'custom_css' => ['nullable', 'string', 'max:5000'],
            'custom_js' => ['nullable', 'string', 'max:5000'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Sanitize validated data before using it.
     *
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $data = parent::validated();

        if (array_key_exists('custom_css', $data)) {
            $data['custom_css'] = strip_tags($data['custom_css'], '<style><br><span><div><p><strong><em><u><a><ul><ol><li><blockquote><hr><code><pre>');
        }

        if (array_key_exists('custom_js', $data)) {
            $data['custom_js'] = strip_tags($data['custom_js']);
        }

        return $data;
    }
}
