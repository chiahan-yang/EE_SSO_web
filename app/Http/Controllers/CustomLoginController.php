<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 用於管理登入狀態
use Illuminate\Support\Facades\Hash; // 用於加密密碼
use App\Models\User;                 // 你的使用者模型
use SoapClient;                      // 舊系統用的 SOAP 客戶端
use Illuminate\Support\Str;          // 產生隨機字串用

class CustomLoginController extends Controller
{
    // 1. 顯示登入表單的頁面
    public function showLoginForm()
    {
        return view('login'); // 對應到 resources/views/login.blade.php
    }

    // 2. 處理登入請求 (POST)
    public function login(Request $request)
    {
        // 驗證表單輸入
        $request->validate([
            'account' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->account;
        $password = $request->password;

        // ==========================================
        // 階段一：嘗試學校 SSO (SOAP) 驗證
        // ==========================================
        $isSsoSuccess = false;
        $ssoData = null;

        try {
            // 設定 WSDL 網址 (從你的舊程式碼複製)
            $wsdlUrl = "http://sso.nsysu.edu.tw/ssoWebservice/wsso.wsdl";
            
            // 建立 SOAP 連線 (cache_wsdl => 0 避免開發時被快取干擾)
            $client = new SoapClient($wsdlUrl, array('trace' => true, 'cache_wsdl' => WSDL_CACHE_NONE));
            
            // 呼叫驗證 (參數依據舊程式碼: account, password, "ENC", "1;2")
            $authlogin = $client->authUser2($username, $password, "ENC", "1;2");

            if ($authlogin) {
                    $isSsoSuccess = true;
                    // 取得詳細資料
                    $infoStr = $client->getAttr2($username, $password, "ENC", "1;2", "EMPNO;NAME;IDNO;PKIND;GRPNO;UNICOD1;DPT_DESC1;UNICOD2;DPT_DESC2;LEAVE;TITCOD;TITLE;EMAIL;POFTEL");
                    
                    $ssoData = explode(";", $infoStr);

                    // 【新增這段】將資料映射成易讀的陣列，並存入 Session
                    $ssoMap = [
                        '員工編號 (EMPNO)' => $ssoData[0] ?? '',
                        '姓名 (NAME)' => $ssoData[1] ?? '',
                        '身分證號 (IDNO)' => $ssoData[2] ?? '', // 注意資安，此欄位通常不顯示
                        '人員類別 (PKIND)' => $ssoData[3] ?? '',
                        '群組代碼 (GRPNO)' => $ssoData[4] ?? '',
                        '單位代碼1 (UNICOD1)' => $ssoData[5] ?? '',
                        '單位名稱1 (DPT_DESC1)' => $ssoData[6] ?? '',
                        '單位代碼2 (UNICOD2)' => $ssoData[7] ?? '',
                        '單位名稱2 (DPT_DESC2)' => $ssoData[8] ?? '',
                        '離職註記 (LEAVE)' => $ssoData[9] ?? '',
                        '職稱代碼 (TITCOD)' => $ssoData[10] ?? '',
                        '職稱名稱 (TITLE)' => $ssoData[11] ?? '',
                        'Email (EMAIL)' => $ssoData[12] ?? '',
                        '辦公室電話 (POFTEL)' => $ssoData[13] ?? '',
                    ];
                    // 存入 Session，讓個人資訊頁面可以讀取
                    session(['sso_info' => $ssoMap]);
                }

        } catch (\Exception $e) {
            // 如果學校主機連不上，這裡會捕捉錯誤，但不中斷，繼續往下嘗試本地登入
            // 你可以用 Log::error($e->getMessage()); 紀錄錯誤
        }

        // ==========================================
        // 階段二：根據驗證結果處理登入
        // ==========================================

        // --- 情況 A：SSO 驗證成功 ---
        if ($isSsoSuccess) {
            // 檢查 Laravel 本地資料庫是否已有此人？
            // 這裡假設用 'email' 欄位存學號/員編 (視你資料庫設計而定)
            $user = User::where('email', $username)->first();

            if (!$user) {
                // 如果是新使用者，自動註冊到本地資料庫
                $user = User::create([
                    'name' => $ssoData[1] ?? $username, // 姓名 (如果抓不到就用帳號)
                    'email' => $username,               // 這裡暫存帳號當 email (因為 User 表 email 是 unique)
                    'password' => Hash::make(Str::random(16)), // SSO 登入不需要本地密碼，隨機產生
                    // 你可以在這裡多存其他欄位，例如 $table->string('sso_id')->nullable();
                ]);
            }

            // 執行 Laravel 登入
            Auth::login($user);
            $request->session()->regenerate(); // 防止 Session 固定攻擊

            return redirect()->intended('/home'); // 登入成功，導向首頁
        }

        // --- 情況 B：SSO 失敗，嘗試本地資料庫驗證 ---
        // 這是給「非學校人員」或「廠商」用的
        if (Auth::attempt(['email' => $username, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        // --- 情況 C：全部失敗 ---
        return back()->withErrors([
            'account' => '帳號或密碼錯誤。',
        ])->withInput(); // 保留輸入的帳號讓使用者不用重打
    }

    // 3. 登出功能
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}