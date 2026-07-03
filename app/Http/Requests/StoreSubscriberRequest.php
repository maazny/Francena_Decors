<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|string|max:10',
            'tags' => 'nullable|array',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:newsletter_groups,id',
        ];
    }
}
