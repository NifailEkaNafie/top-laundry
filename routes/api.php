<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;

/*
|--------------------------------------------------------------------------
| AUTH (JWT)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    // Tambahkan ->name('register.post')
    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    // Tambahkan ->name('login.post')
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::post('/public/booking', [App\Http\Controllers\Api\PublicBookingController::class, 'store']);

/*
|--------------------------------------------------------------------------
| PROTECTED API (JWT)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {

    // CUSTOMER
    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::get('customers/{id}', [CustomerController::class, 'show']);
    Route::put('customers/{id}', [CustomerController::class, 'update']);
    Route::delete('customers/{id}', [CustomerController::class, 'destroy']);

    // ORDER
    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::delete('orders/{id}', [OrderController::class, 'destroy']);
});
