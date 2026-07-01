<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $departmentId = $this->route('team_department')?->id ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:team_departments,slug,'.($departmentId ?? 'NULL')],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['boolean'],
        ];
    }
}
