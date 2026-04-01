<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomLoginController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| 公開路由 (不需要登入即可訪問)
|--------------------------------------------------------------------------
*/

// 根目錄 (/)：自動顯示登入畫面
Route::get('/', [CustomLoginController::class, 'showLoginForm'])->name('root');

// 登入頁面 (GET)：顯示登入表單
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login');

// 執行登入 (POST)：處理 SSO 或本地登入邏輯
Route::post('/login', [CustomLoginController::class, 'login']);

// 執行登出 (POST)：清除 Session 並導回登入頁
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 受保護路由 (必須登入後才能訪問)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // 系統首頁 (Dashboard)
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // SSO 個人詳細資訊頁面 (Profile)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    /*
    |--------------------------------------------------------------------------
    | 帳號管理自訂動作 (必須放在 resource 之前)
    |--------------------------------------------------------------------------
    */
    // 專門處理修改密碼的路由：對應到 UserController 的 updatePassword 方法
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');

    /*
    |--------------------------------------------------------------------------
    | 帳號管理 (標準 CRUD)
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class);

});