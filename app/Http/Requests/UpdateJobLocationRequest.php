<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $location = $this->route('location') ?? $this->route('job_location');
        return $this->user()?->can('update', $location) ?? true;
    }

    public function rules(): array
    {
        $id = $this->route('location') ?? $this->route('job_location');
        if (is_object($id)) {
            $id = $id->id;
        }

        return [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191', Rule::unique('job_locations', 'slug')->ignore($id)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'google_map_embed' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
