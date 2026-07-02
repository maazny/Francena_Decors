<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('blog_post') ?? $this->route('blogPost');
        return $this->user()?->can('update', $post) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('blog_post') ?? $this->route('blogPost');

        return [
            'category_id' => ['nullable', 'exists:blog_categories,id'],
            'author_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('blog_posts', 'slug')->ignore($id)],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'featured_image_id' => ['nullable', 'exists:media,id'],
            'banner_image_id' => ['nullable', 'exists:media,id'],
            'display_order' => ['nullable', 'integer'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_homepage_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:blog_tags,id'],
        ];
    }
}
