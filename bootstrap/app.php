<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Throwable $e, $request) {

            // فقط API
            if (! $request->is('api/*')) {
                return null; // رفتار پیش‌فرض برای web
            }

            // 422 - Validation
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return \App\Http\Responses\ApiResponse::validation($e->errors());
            }

            // 401 - Unauthenticated
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return \App\Http\Responses\ApiResponse::unauthorized();
            }

            // 403 - Forbidden
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return \App\Http\Responses\ApiResponse::forbidden();
            }

            // 404 - Not Found (route/model)
            if (
                $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
            ) {
                return \App\Http\Responses\ApiResponse::notFound();
            }

            // 422 - Domain rule violation
            if ($e instanceof \DomainException) {
                return \App\Http\Responses\ApiResponse::error($e->getMessage(), null, 422);
            }

            // 500 - unexpected
            return \App\Http\Responses\ApiResponse::serverError();
        });
    })->create();
