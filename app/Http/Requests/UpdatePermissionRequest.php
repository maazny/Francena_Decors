<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('permission');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'permission_group_id' => 'required|exists:permission_groups,id',
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
