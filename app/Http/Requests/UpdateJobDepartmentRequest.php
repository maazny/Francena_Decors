<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $department = $this->route('department') ?? $this->route('job_department');
        return $this->user()?->can('update', $department) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('department') ?? $this->route('job_department');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('job_departments', 'slug')->ignore($id)],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
