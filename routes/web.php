<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomLoginController;


// 登入頁面 (GET)
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');

// 送出登入資料 (POST)
Route::post('/login', [CustomLoginController::class, 'login']);

// 登出
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

// 首頁 (登入後才能看)
Route::get('/home', function () {
    return view('home'); // 對應到 resources/views/home.blade.php
})->middleware('auth');