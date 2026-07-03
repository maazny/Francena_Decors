<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('category') ?? $this->route('job_category');
        return $this->user()?->can('update', $category) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('category') ?? $this->route('job_category');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'department_id' => ['required', 'exists:job_departments,id'],
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('job_categories', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
