<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('team_member')?->id ?? null;

        return [
            'department_id' => ['nullable', 'exists:team_departments,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:team_members,slug,'.($memberId ?? 'NULL')],
            'designation' => ['nullable', 'string', 'max:255'],
            'short_bio' => ['nullable', 'string', 'max:500'],
            'full_bio' => ['nullable', 'string'],
            'profile_photo_id' => ['nullable', 'exists:media,id'],
            'cover_photo_id' => ['nullable', 'exists:media,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'experience_years' => ['nullable', 'integer', 'between:0,100'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer'],
            'featured' => ['boolean'],
            'homepage_featured' => ['boolean'],
            'joining_date' => ['nullable', 'date'],
            'status' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
        ];
    }
}
