<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeoRedirectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('redirect') ?? $this->route('seo_redirect');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'source_url' => 'required|string|max:255|unique:seo_redirects,source_url,' . $id,
            'target_url' => 'required|string|max:255',
            'type' => 'required|integer|in:301,302',
            'is_wildcard' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $source = '/' . trim($this->input('source_url'), '/');
            $target = '/' . trim($this->input('target_url'), '/');

            if ($source === $target) {
                $validator->errors()->add('target_url', 'A redirect target cannot match its source (direct loop detected).');
            }
        });
    }
}
