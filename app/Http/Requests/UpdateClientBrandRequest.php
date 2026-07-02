<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:client_brands,slug,'.$this->route('client_brand')],
            'description' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'logo_id' => ['nullable', 'exists:media,id'],
            'category' => ['nullable', 'string', 'max:100'],
            'featured' => ['boolean'],
            'homepage_featured' => ['boolean'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['required', 'in:published,draft'],
        ];
    }
}
