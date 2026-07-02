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
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'banner_image_id' => ['nullable', 'exists:media,id'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:191'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
