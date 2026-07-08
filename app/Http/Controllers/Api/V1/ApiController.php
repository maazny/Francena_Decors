<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\ApiPagination;
use App\Traits\ApiFiltering;
use App\Traits\ApiSorting;

abstract class ApiController extends Controller
{
    use ApiResponse, ApiPagination, ApiFiltering, ApiSorting;
}
