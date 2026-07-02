<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:team_departments,slug'],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['boolean'],
        ];
    }
}
