<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateServiceRequest extends StoreServiceRequest
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
            Rule::unique('services', 'slug')->ignore($this->route('service')),
        ];

        return $rules;
    }
}
