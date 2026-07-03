<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $application = $this->route('application') ?? $this->route('job_application');
        return $this->user()?->can('update', $application) ?? true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'resume_media_id' => ['nullable', 'exists:media,id'],
            'cover_letter' => ['nullable', 'string'],
            'years_of_experience' => ['nullable', 'numeric', 'min:0'],
            'current_company' => ['nullable', 'string', 'max:191'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'application_status' => ['required', 'string', 'max:50'],
            'admin_notes' => ['nullable', 'string'],
        ];
    }
}
