<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tag = $this->route('blog_tag') ?? $this->route('blogTag');
        return $this->user()?->can('update', $tag) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('blog_tag') ?? $this->route('blogTag');

        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('blog_tags', 'slug')->ignore($id)],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
