<?php

namespace App\Enums;

enum SeoPageType: string
{
    case STATIC = 'static';
    case DYNAMIC = 'dynamic';
    case MODULE = 'module';
}
