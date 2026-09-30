<?php

namespace App\Shared\Exceptions;

use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(Exceptions $exceptions): void
    {
        Integration::handles($exceptions);

        $exceptions->context(function () {
            $request = request();

            return [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_id' => Auth::id(),
            ];
        });

        $exceptions->renderable(function (AuthenticationException $e) {
            if ($this->isApiRequest()) {
                return ApiResponse::error(__('Unauthenticated.'), 401);
            }
        });

        $exceptions->renderable(function (ValidationException $e) {
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
                data: ['errors' => $e->errors()]
            );
        });

        $exceptions->renderable(function (ModelNotFoundException $e) {
            return ApiResponse::error('Not found', 404);
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return ApiResponse::error('Not found', 404);
        });

        $exceptions->renderable(function (HttpExceptionInterface $e) {
            if (! $this->isApiRequest()) {
                return null;
            }

            $status = $e->getStatusCode();
            $message = $e->getMessage() !== '' ? $e->getMessage() : match ($status) {
                403 => 'Forbidden',
                404 => 'Not found',
                405 => 'Method not allowed',
                429 => 'Too many requests',
                default => 'Request error',
            };

            return ApiResponse::error($message, $status);
        });

        $exceptions->renderable(function (\Throwable $e) {
            if (! $this->isApiRequest()) {
                return null;
            }

            $debug = (bool) config('app.debug');

            return ApiResponse::error(
                message: $debug ? $e->getMessage() : 'Server error',
                status: 500,
                data: $debug ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace(),
                ] : null
            );
        });
    }

    private function isApiRequest(): bool
    {
        $request = request();

        return $request->wantsJson() || str_starts_with($request->path(), 'api/');
    }
}
