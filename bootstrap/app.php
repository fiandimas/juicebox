<?php

use App\Exceptions\WeatherServiceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (ValidationException $e, Request $request) {
            $errors = collect($e->errors())->map(function ($messages, $field) {
                return [
                    'field' => $field,
                    'message' => $messages[0],
                ];
            })->values();

            return response()->json([
                'error' => 'VALIDATION_ERROR',
                'message' => 'Please check your request',
                'additional_information' => $errors,
            ], 422);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'error' => 'NOT_FOUND',
                'message' => 'Data or route not found',
                'additional_information' => null,
            ], 404);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'error' => 'UNAUTHENTICATED',
                'message' => 'You must logged in first',
                'additional_information' => null,
            ], 401);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            return response()->json([
                'error' => 'UNAUTHENTICATED',
                'message' => "You don't have permission to this data",
                'additional_information' => null,
            ], 403);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'error' => 'TOO_MANY_REQUEST',
                'message' => 'Too Many Attempts. Try again in several minutes',
                'additional_information' => null,
            ], 429);
        });

        $exceptions->render(function (WeatherServiceException $e, Request $request) {
            return response()->json([
                'error' => 'WEATHER_EXCEPTION',
                'message' => $e->getMessage(),
                'additional_information' => null,
            ], 400);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            return response()->json([
                'error' => 'INTERNAL_SERVER_ERROR',
                'message' => $status === 500 ? 'Terjadi kesalahan pada server' : $e->getMessage(),
                'additional_information' => config('app.debug') ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ], $status);
        });
    })->create();
