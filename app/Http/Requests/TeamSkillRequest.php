<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['required', 'string', 'max:255'],
            'skill_percentage' => ['required', 'integer', 'between:0,100'],
            'display_order' => ['nullable', 'integer'],
        ];
    }
}
