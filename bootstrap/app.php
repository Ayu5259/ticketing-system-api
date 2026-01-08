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

            if (! $request->is('api/*')) {
                return null;
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return \App\Http\Responses\ApiResponse::validation($e->errors());
            }

            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return \App\Http\Responses\ApiResponse::unauthorized();
            }

            if (
                $e instanceof \Illuminate\Auth\Access\AuthorizationException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
            ) {
                return \App\Http\Responses\ApiResponse::error($e->getMessage(), null, 403);
            }

            if (
                $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ||
                $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
            ) {
                return \App\Http\Responses\ApiResponse::notFound();
            }

            if ($e instanceof \DomainException) {
                return \App\Http\Responses\ApiResponse::error($e->getMessage(), null, 422);
            }

            return \App\Http\Responses\ApiResponse::serverError();
        });
    })->create();
