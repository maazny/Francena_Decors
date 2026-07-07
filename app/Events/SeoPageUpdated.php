<?php

namespace App\Events;

use App\Models\SeoPage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeoPageUpdated
{
    use Dispatchable, SerializesModels;

    public SeoPage $page;

    public function __construct(SeoPage $page)
    {
        $this->page = $page;
    }
}
