<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $category = $this->route('blog_category') ?? $this->route('blogCategory');
        return $this->user()?->can('update', $category) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('blog_category') ?? $this->route('blogCategory');

        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('blog_categories', 'slug')->ignore($id)],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
