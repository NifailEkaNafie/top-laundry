<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('order', [OrderController::class, 'index']);
    Route::get('order/{id}', [OrderController::class, 'show']);
    Route::post('order', [OrderController::class, 'store']);
    Route::patch('order/{id}', [OrderController::class, 'update']);
    Route::delete('order/{id}', [OrderController::class, 'destroy']);
});
