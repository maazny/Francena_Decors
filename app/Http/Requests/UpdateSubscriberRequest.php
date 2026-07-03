<?php

namespace App\Http\Requests;

use App\Enums\SubscriptionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSubscriberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subscriberId = $this->route('subscriber')?->id ?? $this->route('subscriber');

        return [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:newsletter_subscribers,email,' . $subscriberId,
            'phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|string|max:10',
            'status' => ['required', new Enum(SubscriptionStatus::class)],
            'tags' => 'nullable|array',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:newsletter_groups,id',
        ];
    }
}
