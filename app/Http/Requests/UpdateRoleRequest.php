<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('role');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
