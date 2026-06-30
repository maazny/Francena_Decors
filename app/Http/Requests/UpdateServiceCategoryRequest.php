<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateServiceCategoryRequest extends StoreServiceCategoryRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['slug'] = [
            'nullable',
            'string',
            'max:191',
            Rule::unique('service_categories', 'slug')->ignore($this->route('service_category')),
        ];

        return $rules;
    }
}
