<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Generic success response
     */
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Generic error response
     */
    public static function error(
        string $message = 'Error',
        mixed $errors = null,
        int $status = 400
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * 401 - Unauthenticated
     */
    public static function unauthorized(
        string $message = 'Unauthenticated'
    ): JsonResponse {
        return self::error($message, null, 401);
    }

    /**
     * 403 - Forbidden (authorization failed)
     */
    public static function forbidden(
        string $message = 'Forbidden'
    ): JsonResponse {
        return self::error($message, null, 403);
    }

    /**
     * 404 - Resource or route not found
     */
    public static function notFound(
        string $message = 'Not Found'
    ): JsonResponse {
        return self::error($message, null, 404);
    }

    /**
     * 422 - Validation error
     */
    public static function validation(
        mixed $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return self::error($message, $errors, 422);
    }

    /**
     * 500 - Internal server error
     */
    public static function serverError(
        string $message = 'Server Error'
    ): JsonResponse {
        return self::error($message, null, 500);
    }
}
