<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
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
            'site_name' => ['required', 'string', 'max:150'],
            'company_name' => ['required', 'string', 'max:150'],
            'tagline' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:150'],
            'support_email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s\-]+$/'],
            'mobile' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s\-]+$/'],
            'whatsapp' => ['required', 'string', 'max:30', 'regex:/^[0-9+()\s\-]+$/'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'google_map' => ['nullable', 'url', 'max:500'],
            'office_hours' => ['required', 'string', 'max:150'],
            'copyright' => ['required', 'string', 'max:255'],
            'footer_text' => ['required', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp,ico', 'max:2048'],
            'footer_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp,ico', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp,ico', 'max:2048'],
            'default_language' => ['required', 'string', 'max:20'],
            'timezone' => ['required', 'string', 'max:150'],
            'maintenance_mode' => ['sometimes', 'boolean'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'status' => ['sometimes', 'boolean'],
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
            'phone.regex' => 'Phone number may only contain digits, spaces, plus signs, parentheses, and hyphens.',
            'mobile.regex' => 'Mobile number may only contain digits, spaces, plus signs, parentheses, and hyphens.',
            'whatsapp.regex' => 'WhatsApp number may only contain digits, spaces, plus signs, parentheses, and hyphens.',
        ];
    }
}
