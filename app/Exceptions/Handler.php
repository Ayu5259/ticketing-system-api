<?php

namespace App\Exceptions;

use App\Http\Responses\ApiResponse;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * 401 – Unauthenticated
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->is('api/*')) {
            return ApiResponse::unauthorized();
        }

        return parent::unauthenticated($request, $exception);
    }

    /**
     * 422 – Validation (postJson)
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        if ($request->is('api/*')) {
            return ApiResponse::validation($exception->errors());
        }

        return parent::invalidJson($request, $exception);
    }

    /**
     * ⭐️ این متد کلیدی است
     * همهٔ exceptionها (حتی در DEBUG) از اینجا عبور می‌کنند
     */
    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {

            if ($e instanceof ValidationException) {
                return ApiResponse::validation($e->errors());
            }

            if ($e instanceof AuthenticationException) {
                return ApiResponse::unauthorized();
            }

            if ($e instanceof AuthorizationException) {
                return ApiResponse::forbidden();
            }

            if (
                $e instanceof ModelNotFoundException ||
                $e instanceof NotFoundHttpException
            ) {
                return ApiResponse::notFound();
            }

            if ($e instanceof DomainException) {
                return ApiResponse::error($e->getMessage(), null, 422);
            }

            // 500 – حتی در APP_DEBUG=true
            return ApiResponse::serverError();
        }

        return parent::render($request, $e);
    }
}
