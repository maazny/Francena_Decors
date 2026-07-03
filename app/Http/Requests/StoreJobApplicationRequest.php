<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Candidates can submit applications publicly
    }

    public function rules(): array
    {
        return [
            'job_opening_id' => ['required', 'exists:job_openings,id'],
            'full_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // candidate uploaded file
            'resume_media_id' => ['nullable', 'exists:media,id'], //picked media id (admin panel)
            'cover_letter' => ['nullable', 'string'],
            'years_of_experience' => ['nullable', 'numeric', 'min:0'],
            'current_company' => ['nullable', 'string', 'max:191'],
            'expected_salary' => ['nullable', 'numeric', 'min:0'],
            'application_status' => ['sometimes', 'string', 'max:50'],
            'admin_notes' => ['nullable', 'string'],
        ];
    }
}
