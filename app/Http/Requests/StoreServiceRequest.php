<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:service_categories,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:services,slug'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:20000'],
            'featured_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'banner_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'icon' => ['nullable', 'string', 'max:100'],
            'starting_price' => ['nullable', 'numeric', 'between:0,999999999.99'],
            'duration' => ['nullable', 'string', 'max:191'],
            'location' => ['nullable', 'string', 'max:191'],
            'display_order' => ['required', 'integer', 'between:0,9999'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }
}
