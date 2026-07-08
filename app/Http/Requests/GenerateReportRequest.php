<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ReportType;
use App\Enums\ReportPeriod;

/**
 * Class GenerateReportRequest
 * @package App\Http\Requests
 */
class GenerateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('analytics.reports') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'report_name' => ['required', 'string', 'max:255'],
            'report_type' => ['required', new Enum(ReportType::class)],
            'period' => ['required', new Enum(ReportPeriod::class)],
            'start_date' => ['required', 'date', 'before_or_equal:end_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'filters' => ['nullable', 'array'],
        ];
    }
}
