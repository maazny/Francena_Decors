<?php

namespace App\Events;

use App\Models\SeoRedirect;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RedirectCreated
{
    use Dispatchable, SerializesModels;

    public SeoRedirect $redirect;

    public function __construct(SeoRedirect $redirect)
    {
        $this->redirect = $redirect;
    }
}
