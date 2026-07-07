<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'permission_group_id' => 'required|exists:permission_groups,id',
            'name' => 'required|string|max:255|unique:permissions,name',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
