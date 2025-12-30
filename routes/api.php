<?php

use Illuminate\Support\Facades\Route;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {

    Route::get('/health', function () {
        return ApiResponse::success(
            ['service' => 'ticketing-system-api'],
            'Healthy'
        );
    });

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });
});
