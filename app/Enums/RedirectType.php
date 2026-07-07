<?php

namespace App\Enums;

enum RedirectType: int
{
    case PERMANENT = 301;
    case TEMPORARY = 302;
}
