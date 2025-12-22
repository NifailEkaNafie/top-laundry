<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DUMMY LOGIN ROUTE (WAJIB UNTUK API AUTH)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return response()->json([
        'message' => 'Unauthenticated'
    ], 401);
})->name('login');
