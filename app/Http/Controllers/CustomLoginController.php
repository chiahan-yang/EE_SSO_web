<?php
// 1150324 還原可登入版本
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\SSOService;
use Illuminate\Support\Str;

class CustomLoginController extends Controller
{
    // 1. 定義屬性
    protected $ssoService;

    // 2. 透過建構子注入 SSOService
    public function __construct(SSOService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    // 3. 顯示登入表單
    public function showLoginForm()
    {
        return view('login');
    }

    // 4. 處理登入請求 (主要修正邏輯在此)
    public function login(Request $request)
    {
        $request->validate([
            'account' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('account', 'password');

        // --- 階段一：嘗試 SSO 登入 ---
        $ssoResult = $this->ssoService->authenticate($credentials['account'], $credentials['password']);

        if ($ssoResult['success']) {
            // 存入完整 Session 資料，供頁面查看 (包含 EMPNO, NAME, IDNO 等 14 個欄位)
            session(['sso_info' => $ssoResult['map']]);

            // 找到或建立本地 User 紀錄 (解決 1364 Error: 確保填入 account 與 user_type)
            $user = User::updateOrCreate(
                ['account' => $ssoResult['account']], // 以員編/帳號作為唯一識別
                [
                    'name'      => $ssoResult['name'],
                    'email'     => $ssoResult['email'] ?? ($ssoResult['account'] . '@nsysu.edu.tw'),
                    'user_type' => 'sso', // 標記為 SSO 會員
                    'password'  => Hash::make(Str::random(24)), // SSO 會員使用隨機密碼
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        // --- 階段二：SSO 失敗，嘗試本地驗證 ---
        // 這裡改用 account 欄位去驗證，因為我們已經定義 User Model 使用 account 識別
        if (Auth::attempt(['account' => $credentials['account'], 'password' => $credentials['password']])) {
            
            // 安全檢查：確保本地登入的是 'local' 類型的會員 (防止 SSO 會員繞過驗證)
            if (Auth::user()->user_type !== 'local') {
                Auth::logout();
                return back()->withErrors(['account' => '此帳號為 SSO 會員，請使用學校密碼登入']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        // --- 階段三：全部失敗 ---
        return back()->withErrors(['account' => '帳號或密碼錯誤。'])->withInput();
    }

    // 5. 登出功能
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}