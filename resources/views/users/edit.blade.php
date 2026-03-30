@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>編輯帳號資料</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card {{ $user->user_type == 'sso' ? 'card-secondary' : 'card-info' }}">
            <div class="card-header">
                <h3 class="card-title">{{ $user->user_type == 'sso' ? 'SSO 自動同步帳號 (部分欄位唯讀)' : '本地管理帳號 (全欄位開放)' }}</h3>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>登入帳號</label>
                                <input type="text" name="account" class="form-control" value="{{ old('account', $user->account) }}" 
                                    {{ $user->user_type == 'sso' ? 'readonly' : '' }} required>
                            </div>
                            <div class="form-group">
                                <label>姓名</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>身分證號 (IDNO)</label>
                                <input type="text" name="idno" class="form-control" value="{{ old('idno', $user->idno) }}" 
                                    {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>群組 (GRPNO)</label>
                                <input type="text" name="grpno" class="form-control" value="{{ old('grpno', $user->grpno) }}" 
                                    {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                            </div>
                            <div class="form-group">
                                <label>單位名稱 (單位代碼)</label>
                                <div class="input-group">
                                    <input type="text" name="dpt_desc1" class="form-control" placeholder="單位名稱" value="{{ old('dpt_desc1', $user->dpt_desc1) }}" {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                                    <input type="text" name="unicode1" class="form-control" placeholder="代碼" value="{{ old('unicode1', $user->unicode1) }}" {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>職稱名稱 (職稱代碼)</label>
                                <div class="input-group">
                                    <input type="text" name="title" class="form-control" placeholder="職稱名稱" value="{{ old('title', $user->title) }}" {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                                    <input type="text" name="titcod" class="form-control" placeholder="代碼" value="{{ old('titcod', $user->titcod) }}" {{ $user->user_type == 'sso' ? 'readonly' : '' }}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>在職/在學狀態 (LEAVE)</label>
                                <select name="leave" class="form-control" {{ $user->user_type == 'sso' ? 'disabled' : '' }}>
                                    <option value="0" {{ old('leave', $user->leave) == '0' ? 'selected' : '' }}>在職/在學 (0)</option>
                                    <option value="1" {{ old('leave', $user->leave) == '1' ? 'selected' : '' }}>離職/畢業 (1)</option>
                                </select>
                                @if($user->user_type == 'sso') <input type="hidden" name="leave" value="{{ $user->leave }}"> @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-info">儲存修改</button>
                    <a href="/users" class="btn btn-default">取消</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection