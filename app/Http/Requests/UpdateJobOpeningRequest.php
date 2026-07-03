<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobOpeningRequest extends FormRequest
{
    public function authorize(): bool
    {
        $opening = $this->route('job') ?? $this->route('job_opening');
        return $this->user()?->can('update', $opening) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('job') ?? $this->route('job_opening');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'department_id' => ['required', 'exists:job_departments,id'],
            'category_id' => ['required', 'exists:job_categories,id'],
            'location_id' => ['required', 'exists:job_locations,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('job_openings', 'slug')->ignore($id)],
            'reference_no' => ['nullable', 'string', 'max:50', Rule::unique('job_openings', 'reference_no')->ignore($id)],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['required', 'string'],
            'employment_type' => ['required', 'string', 'max:100'],
            'experience_level' => ['required', 'string', 'max:100'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'salary_from' => ['nullable', 'numeric', 'min:0'],
            'salary_to' => ['nullable', 'numeric', 'min:0'],
            'salary_type' => ['nullable', 'string', 'max:50'],
            'application_deadline' => ['nullable', 'date'],
            'featured' => ['sometimes', 'boolean'],
            'homepage_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['required', 'string', 'max:191'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['required', 'string', 'max:191'],
            'qualifications' => ['nullable', 'array'],
            'qualifications.*' => ['required', 'string', 'max:191'],
        ];
    }
}
