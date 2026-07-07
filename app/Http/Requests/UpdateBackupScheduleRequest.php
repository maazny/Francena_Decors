<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BackupType;
use App\Enums\BackupFrequency;

class UpdateBackupScheduleRequest extends FormRequest
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
            'schedule_name' => 'required|string|max:255',
            'backup_type' => [
                'required',
                Rule::enum(BackupType::class),
            ],
            'frequency' => [
                'required',
                Rule::enum(BackupFrequency::class),
            ],
            'cron_expression' => 'nullable|required_if:frequency,custom|string|max:100',
            'storage_disk' => 'nullable|string|in:local,public,s3',
            'retain_backups' => 'nullable|integer|min:1|max:365',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
