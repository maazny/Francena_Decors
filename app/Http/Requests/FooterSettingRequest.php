<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FooterSettingRequest extends FormRequest
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
            'layout' => ['required', Rule::in(['four_columns', 'three_columns', 'two_columns', 'stacked'])],
            'logo_media_id' => ['nullable', 'exists:media,id'],
            'background_media_id' => ['nullable', 'exists:media,id'],
            'company_description' => ['nullable', 'string', 'max:1000'],
            'show_logo' => ['sometimes', 'boolean'],
            'show_description' => ['sometimes', 'boolean'],
            'show_columns' => ['sometimes', 'boolean'],
            'show_contact' => ['sometimes', 'boolean'],
            'show_business_hours' => ['sometimes', 'boolean'],
            'show_social_links' => ['sometimes', 'boolean'],
            'show_widgets' => ['sometimes', 'boolean'],
            'newsletter_enabled' => ['sometimes', 'boolean'],
            'newsletter_title' => ['nullable', 'string', 'max:191'],
            'newsletter_description' => ['nullable', 'string', 'max:500'],
            'newsletter_placeholder' => ['nullable', 'string', 'max:191'],
            'newsletter_button_text' => ['nullable', 'string', 'max:100'],
            'contact_heading' => ['nullable', 'string', 'max:191'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'contact_phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9+()\s\-]+$/'],
            'contact_email' => ['nullable', 'email', 'max:191'],
            'business_hours_heading' => ['nullable', 'string', 'max:191'],
            'copyright_text' => ['nullable', 'string', 'max:500'],
            'bottom_bar_enabled' => ['sometimes', 'boolean'],
            'bottom_bar_text' => ['nullable', 'string', 'max:255'],
            'background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'heading_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'link_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'bottom_background_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'status' => ['sometimes', 'boolean'],

            'columns' => ['nullable', 'array', 'max:6'],
            'columns.*.title' => ['nullable', 'string', 'max:191'],
            'columns.*.type' => ['nullable', Rule::in(['quick_links', 'services', 'useful_links', 'links'])],
            'columns.*.sort_order' => ['nullable', 'integer', 'between:0,100'],
            'columns.*.status' => ['sometimes', 'boolean'],
            'columns.*.links' => ['nullable', 'array', 'max:12'],
            'columns.*.links.*.label' => ['nullable', 'string', 'max:191'],
            'columns.*.links.*.url' => ['nullable', 'string', 'max:500'],
            'columns.*.links.*.target' => ['nullable', Rule::in(['_self', '_blank'])],
            'columns.*.links.*.icon' => ['nullable', 'string', 'max:100'],
            'columns.*.links.*.sort_order' => ['nullable', 'integer', 'between:0,100'],
            'columns.*.links.*.status' => ['sometimes', 'boolean'],

            'social_links' => ['nullable', 'array', 'max:10'],
            'social_links.*.platform' => ['nullable', 'string', 'max:100'],
            'social_links.*.url' => ['nullable', 'url', 'max:500'],
            'social_links.*.icon' => ['nullable', 'string', 'max:100'],
            'social_links.*.sort_order' => ['nullable', 'integer', 'between:0,100'],
            'social_links.*.status' => ['sometimes', 'boolean'],

            'business_hours' => ['nullable', 'array', 'max:10'],
            'business_hours.*.day_label' => ['nullable', 'string', 'max:100'],
            'business_hours.*.time_label' => ['nullable', 'string', 'max:150'],
            'business_hours.*.sort_order' => ['nullable', 'integer', 'between:0,100'],
            'business_hours.*.status' => ['sometimes', 'boolean'],

            'widgets' => ['nullable', 'array', 'max:6'],
            'widgets.*.title' => ['nullable', 'string', 'max:191'],
            'widgets.*.content' => ['nullable', 'string', 'max:1000'],
            'widgets.*.icon' => ['nullable', 'string', 'max:100'],
            'widgets.*.sort_order' => ['nullable', 'integer', 'between:0,100'],
            'widgets.*.status' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Customize the validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_phone.regex' => 'Contact phone may only contain digits, spaces, plus signs, parentheses, and hyphens.',
        ];
    }
}
