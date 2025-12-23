<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Hanya untuk Tampilan)
|--------------------------------------------------------------------------
|
| Route ini hanya bertugas me-load file blade. Logika autentikasi & data
| ditangani oleh JavaScript yang memanggil API (routes/api.php).
|
*/

// Halaman Utama: Redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman Login & Register (Tanpa Controller, langsung View)
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Halaman Dashboard
Route::view('/dashboard', 'dashboard')->name('dashboard');

// Tambahkan baris ini
Route::view('/booking', 'booking')->name('booking');

// Route root bisa diarahkan ke booking jika mau, atau tetap ke login
Route::get('/', function () {
    // Opsional: Redirect user umum ke halaman booking, bukan login admin
    return redirect()->route('booking');
});
