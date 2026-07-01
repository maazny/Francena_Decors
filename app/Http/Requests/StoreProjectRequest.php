<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:projects,slug'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'client_name' => ['nullable', 'string', 'max:191'],
            'client_company' => ['nullable', 'string', 'max:191'],
            'client_logo_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'budget' => ['nullable', 'numeric', 'between:0,999999999999.99'],
            'currency' => ['nullable', 'string', 'max:10'],
            'project_manager' => ['nullable', 'string', 'max:191'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'completion_percentage' => ['nullable', 'integer', 'between:0,100'],
            'status' => ['required', 'in:draft,published,archived'],
            'featured' => ['sometimes', 'boolean'],
            'homepage_featured' => ['sometimes', 'boolean'],
            'location' => ['nullable', 'string', 'max:191'],
            'google_map_embed' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'cover_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'banner_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'video_url' => ['nullable', 'url', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'project_duration' => ['nullable', 'string', 'max:191'],
            'project_area' => ['nullable', 'string', 'max:191'],
            'display_order' => ['required', 'integer', 'between:0,9999'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }
}
