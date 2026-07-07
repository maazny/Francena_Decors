<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ActivityAction;
use App\Enums\ActivityStatus;

class ActivityLogFilterRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
            'module' => 'nullable|string|max:100',
            'action' => [
                'nullable',
                Rule::enum(ActivityAction::class),
            ],
            'status' => [
                'nullable',
                Rule::enum(ActivityStatus::class),
            ],
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'keyword' => 'nullable|string|max:255',
            'ip_address' => 'nullable|ip',
            'browser' => 'nullable|string|max:100',
            'device' => 'nullable|string|max:100',
            'operating_system' => 'nullable|string|max:100',
            'export_type' => 'nullable|string|in:csv,excel,pdf,print',
        ];
    }
}
