<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BackupType;

class CreateBackupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'backup_name' => 'nullable|string|max:255',
            'backup_type' => [
                'required',
                Rule::enum(BackupType::class),
            ],
            'description' => 'nullable|string|max:1000',
            'storage_disk' => 'nullable|string|in:local,public,s3',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
