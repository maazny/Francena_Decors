<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeoPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page_type' => 'required|string|in:static,dynamic,module',
            'page_key' => 'nullable|string|max:255|unique:seo_pages,page_key',
            'slug' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'meta_keywords' => 'nullable|string|max:1000',
            'canonical_url' => 'nullable|url|max:255',
            'robots' => 'nullable|string|max:100',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:1000',
            'og_image' => 'nullable|string|max:255',
            'twitter_card' => 'nullable|string|max:50',
            'custom_head_scripts' => 'nullable|string',
            'custom_footer_scripts' => 'nullable|string',
            'seo_pageable_type' => 'nullable|string|max:255',
            'seo_pageable_id' => 'nullable|integer',
        ];
    }
}
