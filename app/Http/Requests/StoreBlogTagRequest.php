<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\BlogTag::class) ?? true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:blog_tags,slug'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
