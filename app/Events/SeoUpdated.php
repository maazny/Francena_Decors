<?php

namespace App\Events;

use App\Models\SeoSetting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeoUpdated
{
    use Dispatchable, SerializesModels;

    public SeoSetting $setting;

    public function __construct(SeoSetting $setting)
    {
        $this->setting = $setting;
    }
}
