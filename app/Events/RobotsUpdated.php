<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RobotsUpdated
{
    use Dispatchable, SerializesModels;

    public string $rules;

    public function __construct(string $rules)
    {
        $this->rules = $rules;
    }
}
