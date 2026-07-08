<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ApiResponseHelper
{
    /**
     * Return a standardized success JSON response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array  $meta
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        // Format metadata
        if (empty($meta)) {
            $response['meta'] = (object) [];
        } else {
            $response['meta'] = $meta;
        }

        $response['errors'] = null;

        if (config('api.response_timestamp', true)) {
            $response['timestamp'] = Carbon::now()->toIso8601String();
        }

        if (config('api.request_id', true)) {
            $response['request_id'] = self::getRequestId();
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized error JSON response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed  $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'An error occurred', int $statusCode = 500, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => null,
            'meta' => null,
            'errors' => $errors,
        ];

        if (config('api.response_timestamp', true)) {
            $response['timestamp'] = Carbon::now()->toIso8601String();
        }

        if (config('api.request_id', true)) {
            $response['request_id'] = self::getRequestId();
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Retrieve or generate the request ID for the current request.
     *
     * @return string
     */
    protected static function getRequestId(): string
    {
        if (request() && request()->attributes->has('request_id')) {
            return request()->attributes->get('request_id');
        }

        $requestId = 'req_' . Str::random(16);
        if (request()) {
            request()->attributes->set('request_id', $requestId);
        }

        return $requestId;
    }
}
