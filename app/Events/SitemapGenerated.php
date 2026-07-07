<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SitemapGenerated
{
    use Dispatchable, SerializesModels;

    public string $sitemapPath;

    public function __construct(string $sitemapPath)
    {
        $this->sitemapPath = $sitemapPath;
    }
}
