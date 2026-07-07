<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStructuredDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seo_page_id' => 'nullable|exists:seo_pages,id',
            'type' => 'required|string|in:organization,local_business,website,webpage,breadcrumb,article,faq,service,contact,gallery,person,custom',
            'schema_data' => 'nullable|array',
            'custom_json_ld' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $custom = $this->input('custom_json_ld');
            if ($custom) {
                // Ensure it is valid JSON if provided
                json_decode($custom);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $validator->errors()->add('custom_json_ld', 'Custom JSON-LD must be a valid JSON string.');
                }
            }
        });
    }
}
