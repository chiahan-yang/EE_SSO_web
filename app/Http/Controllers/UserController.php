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
        $users = User::all();
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email, // 本地帳號用 Email 當帳號
            'password' => Hash::make($request->password),
        ]);

        return redirect('/users')->with('success', '帳號建立成功！');
    }

    // 刪除邏輯
    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/users')->with('success', '帳號已刪除！');
    }
}