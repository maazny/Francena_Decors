<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceFaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['nullable', 'string', 'max:3000'],
            'display_order' => ['required', 'integer', 'between:0,9999'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
