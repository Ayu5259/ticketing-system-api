<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Responses\ApiResponse;

Route::prefix('v1')->group(function () {

    Route::get('health', fn() => ApiResponse::success(
        ['service' => 'ticketing-system-api'],
        'Healthy'
    ));

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('tickets', [TicketController::class, 'store']);
        Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply']);
        Route::post('tickets/{ticket}/close', [TicketController::class, 'close']);
    });
    //TMP
    Route::get('debug/boom', function () {
        throw new \RuntimeException('BOOM_HANDLER_TEST');
    });
});
