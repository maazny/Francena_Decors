<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamSocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'max:100'],
            'url' => ['required', 'url', 'max:1000'],
            'display_order' => ['nullable', 'integer'],
        ];
    }
}
