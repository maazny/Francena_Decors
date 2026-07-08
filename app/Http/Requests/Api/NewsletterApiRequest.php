<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\StoreSubscriberRequest;

class NewsletterApiRequest extends StoreSubscriberRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
