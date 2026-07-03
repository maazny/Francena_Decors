<?php

namespace App\Http\Requests;

use App\Enums\ContactPriority;
use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_category_id' => ['nullable', 'exists:contact_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,gif', 'max:10240'], // 10MB limit
            'source' => ['nullable', new Enum(ContactSource::class)],
            'status' => ['nullable', new Enum(ContactStatus::class)],
            'priority' => ['nullable', new Enum(ContactPriority::class)],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'follow_up_at' => ['nullable', 'date'],
            'is_read' => ['nullable', 'boolean'],
        ];
    }
}
