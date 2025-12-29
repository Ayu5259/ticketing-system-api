<?php

use Illuminate\Support\Facades\Route;
use App\Http\Responses\ApiResponse;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return ApiResponse::success(
            ['service' => 'ticketing-system-api'],
            'Healthy'
        );
    });
});
