<?php

namespace App\Traits;

use App\Helpers\ApiResponseHelper;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a standardized success JSON response.
     */
    protected function success($data = null, string $message = 'Success', int $statusCode = 200, array $meta = []): JsonResponse
    {
        return ApiResponseHelper::success($data, $message, $statusCode, $meta);
    }

    /**
     * Return a 201 Created JSON response.
     */
    protected function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return ApiResponseHelper::success($data, $message, 201);
    }

    /**
     * Return a 200 Updated JSON response.
     */
    protected function updated($data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return ApiResponseHelper::success($data, $message, 200);
    }

    /**
     * Return a 200 Deleted JSON response.
     */
    protected function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return ApiResponseHelper::success(null, $message, 200);
    }

    /**
     * Return a standard error JSON response.
     */
    protected function error(string $message = 'An error occurred', int $statusCode = 500, $errors = null): JsonResponse
    {
        return ApiResponseHelper::error($message, $statusCode, $errors);
    }

    /**
     * Return a 422 Validation Error JSON response.
     */
    protected function validationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return ApiResponseHelper::error($message, 422, $errors);
    }

    /**
     * Return a 401 Unauthorized JSON response.
     */
    protected function unauthorized(string $message = 'Authentication required'): JsonResponse
    {
        return ApiResponseHelper::error($message, 401);
    }

    /**
     * Return a 403 Forbidden JSON response.
     */
    protected function forbidden(string $message = 'Action unauthorized'): JsonResponse
    {
        return ApiResponseHelper::error($message, 403);
    }

    /**
     * Return a 404 Not Found JSON response.
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return ApiResponseHelper::error($message, 404);
    }

    /**
     * Return a 500 Internal Server Error JSON response.
     */
    protected function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return ApiResponseHelper::error($message, 500);
    }
}
