<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $groupId = $this->route('group')?->id ?? $this->route('group');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:newsletter_groups,slug,' . $groupId,
            'description' => 'nullable|string',
            'is_dynamic' => 'boolean',
            'filters' => 'nullable|array',
            'status' => 'boolean',
            'display_order' => 'integer',
        ];
    }
}
