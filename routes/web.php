<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomLoginController;


//  (根目錄) 時，顯示登入畫面
Route::get('/', [CustomLoginController::class, 'showLoginForm'])->name('root');

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

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth');

Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create']);
Route::post('/users', [App\Http\Controllers\UserController::class, 'store']);
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');