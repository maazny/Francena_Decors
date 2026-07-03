<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobOpeningRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\JobOpening::class) ?? true;
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:job_departments,id'],
            'category_id' => ['required', 'exists:job_categories,id'],
            'location_id' => ['required', 'exists:job_locations,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:job_openings,slug'],
            'reference_no' => ['nullable', 'string', 'max:50', 'unique:job_openings,reference_no'],
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
