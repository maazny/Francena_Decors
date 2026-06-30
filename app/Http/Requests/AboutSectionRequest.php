<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AboutSectionRequest extends FormRequest
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
            'company_story' => ['nullable', 'string', 'max:5000'],
            'mission' => ['nullable', 'string', 'max:2000'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'chairman_message' => ['nullable', 'string', 'max:3000'],
            'chairman_name' => ['nullable', 'string', 'max:191'],
            'chairman_designation' => ['nullable', 'string', 'max:191'],
            'chairman_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'company_video_id' => ['nullable', Rule::exists('media', 'id')->where(fn ($query) => $query->where('mime_type', 'like', 'video/%'))],
            'brochure_file_id' => ['nullable', Rule::exists('media', 'id')->where('mime_type', 'application/pdf')],
            'experience_years' => ['required', 'integer', 'between:0,999999'],
            'completed_projects' => ['required', 'integer', 'between:0,999999'],
            'happy_clients' => ['required', 'integer', 'between:0,999999'],
            'team_members' => ['required', 'integer', 'between:0,999999'],
            'meta_title' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image_id' => ['nullable', Rule::exists('media', 'id')->where('is_image', true)],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
