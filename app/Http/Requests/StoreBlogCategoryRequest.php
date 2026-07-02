<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\BlogCategory::class) ?? true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:blog_categories,slug'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
