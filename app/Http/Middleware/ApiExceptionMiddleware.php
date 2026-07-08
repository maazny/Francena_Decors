<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ApiExceptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('api/*') && !$request->expectsJson()) {
            return $next($request);
        }

        try {
            $response = $next($request);

            if (isset($response->exception) && $response->exception instanceof Throwable) {
                return $this->handleException($response->exception);
            }

            return $response;
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Format and return JSON response for the given exception.
     *
     * @param  Throwable  $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleException(Throwable $e)
    {
        $statusCode = 500;
        $message = $e->getMessage();
        $errors = null;

        if ($e instanceof ModelNotFoundException) {
            $statusCode = 404;
            $modelName = class_basename($e->getModel());
            $message = "{$modelName} not found.";
        } elseif ($e instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message = $e->getMessage() ?: 'Resource not found.';
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $message = 'Method not allowed for this route.';
        } elseif ($e instanceof ValidationException) {
            $statusCode = 422;
            $message = $e->getMessage() ?: 'Validation failed.';
            $errors = $e->errors();
        } elseif ($e instanceof AuthenticationException) {
            $statusCode = 401;
            $message = $e->getMessage() ?: 'Unauthenticated.';
        } elseif ($e instanceof AccessDeniedHttpException || $e instanceof \Illuminate\Auth\AccessDeniedException) {
            $statusCode = 403;
            $message = $e->getMessage() ?: 'This action is unauthorized.';
        } elseif ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();
        }

        if ($statusCode === 500 && !config('app.debug')) {
            $message = 'Server Error.';
        }

        if (config('app.debug') && $statusCode === 500) {
            $errors = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->take(10)->map(function ($trace) {
                    return array_intersect_key($trace, array_flip(['file', 'line', 'function', 'class']));
                })->all(),
            ];
        }

        return ApiResponseHelper::error($message, $statusCode, $errors);
    }
}
