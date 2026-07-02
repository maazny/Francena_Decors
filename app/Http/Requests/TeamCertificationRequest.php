<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamCertificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'organization' => ['nullable', 'string', 'max:255'],
            'issue_date' => ['nullable', 'date'],
            'certificate_file_id' => ['nullable', 'exists:media,id'],
            'display_order' => ['nullable', 'integer'],
        ];
    }
}
