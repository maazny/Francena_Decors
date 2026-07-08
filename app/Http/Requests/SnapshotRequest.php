<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\MetricType;

/**
 * Class SnapshotRequest
 * @package App\Http\Requests
 */
class SnapshotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('analytics.snapshots') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'snapshot_name' => ['required', 'string', 'max:255'],
            'metric_type' => ['required', new Enum(MetricType::class)],
            'module' => ['required', 'string', 'max:100'],
            'metric_key' => ['required', 'string', 'max:150'],
            'metric_value' => ['required', 'numeric'],
            'metric_data' => ['nullable', 'array'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
