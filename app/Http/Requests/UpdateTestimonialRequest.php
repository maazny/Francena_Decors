<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'testimonial_category_id' => ['nullable', 'exists:testimonial_categories,id'],
            'client_name' => ['required', 'string', 'max:191'],
            'client_company' => ['nullable', 'string', 'max:191'],
            'client_designation' => ['nullable', 'string', 'max:191'],
            'client_photo_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'client_logo_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'project_id' => ['nullable', 'exists:projects,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:191'],
            'testimonial' => ['required', 'string'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'location' => ['nullable', 'string', 'max:191'],
            'featured' => ['sometimes', 'boolean'],
            'homepage_featured' => ['sometimes', 'boolean'],
            'display_order' => ['required', 'integer', 'between:0,9999'],
            'status' => ['required', 'in:draft,published,archived'],
            'approved_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required' => 'Client name is required.',
            'rating.required' => 'Star rating is required.',
            'rating.between' => 'Rating must be between 1 and 5 stars.',
            'testimonial.required' => 'Testimonial content is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be draft, published, or archived.',
        ];
    }
}
