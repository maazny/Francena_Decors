<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StoreContactRequest;

class ContactApiRequest extends StoreContactRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
