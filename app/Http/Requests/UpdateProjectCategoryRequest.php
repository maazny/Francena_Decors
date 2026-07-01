<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProjectCategoryRequest extends StoreProjectCategoryRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['slug'] = [
            'nullable',
            'string',
            'max:191',
            Rule::unique('project_categories', 'slug')->ignore($this->route('projectCategory')),
        ];

        return $rules;
    }
}
