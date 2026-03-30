<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 列表頁
    public function index()
    {
        // 依據 user_type 排序（sso 或 local 會排在一起），再依建立時間排序
        $users = User::orderBy('user_type', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('users.index', compact('users'));
    }

    // 新增頁
    public function create()
    {
        return view('users.create');
    }

    // 儲存邏輯
    public function store(Request $request)
    {
        // 1. 調整驗證邏輯：改為驗證 account 唯一性
        $request->validate([
            'name'     => 'required|string|max:255',
            'account'  => 'required|string|max:50|unique:users,account', // 確保帳號不重複
            'email'    => 'nullable|email|max:255', // 本地帳號 Email 改為選填
            'password' => 'required|min:4',
        ]);

        // 2. 建立本地帳號
        User::create([
            'name'      => $request->name,
            'account'   => $request->account, // 存入自訂帳號
            'email'     => $request->email,    // 存入 Email (若無則為 null)
            'user_type' => 'local',            // 強制標記為本地會員
            'password'  => Hash::make($request->password),
        ]);

        return redirect('/users')->with('success', '本地帳號建立成功！');
    }

    // 刪除邏輯
    public function destroy($id)
    {
       $user = User::findOrFail($id);
    // 修正：比對登入者的 account 與要刪除目標的 account
        if (auth()->user()->account === $user->account) 
        {
              return redirect('/users')->with('error', '安全防護：您不能刪除目前登入的帳號！');
        }

         $user->delete();
        return redirect('/users')->with('success', '帳號「' . $user->name . '」已成功刪除。');
    }                  
    
    public function edit($id)
    {
            // 1. 抓取該使用者資料
            $user = User::findOrFail($id);

            // 2. 顯示編輯頁面 (resources/views/users/edit.blade.php)
            return view('users.edit', compact('user'));
    }


     public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. 驗證基礎欄位
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
        ];

        // 如果是本地帳號，才允許驗證並修改 account (避免重複)
        if ($user->user_type === 'local') {
            $rules['account'] = 'required|string|unique:users,account,' . $id;
        }

        $request->validate($rules);

        // 2. 準備更新資料 (基礎)
        $updateData = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        // 3. 如果是本地帳號，則允許更新所有手動欄位
        if ($user->user_type === 'local') {
            $updateData['account']   = $request->account;
            $updateData['idno']      = $request->idno;
            $updateData['grpno']     = $request->grpno;
            $updateData['unicode1']  = $request->unicode1;
            $updateData['dpt_desc1'] = $request->dpt_desc1;
            $updateData['titcod']    = $request->titcod;
            $updateData['title']     = $request->title;
            $updateData['leave']     = $request->leave;
        }

        $user->update($updateData);

        return redirect('/users')->with('success', '帳號「' . $user->name . '」資料已更新。');
    }
}