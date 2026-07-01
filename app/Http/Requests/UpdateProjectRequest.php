<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProjectRequest extends StoreProjectRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['slug'] = [
            'nullable',
            'string',
            'max:191',
            Rule::unique('projects', 'slug')->ignore($this->route('project')),
        ];

        return $rules;
    }
}
