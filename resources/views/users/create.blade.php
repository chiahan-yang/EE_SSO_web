@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>新增本地帳號</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-primary">
            <form action="/users" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>姓名</label>
                        <input type="text" name="name" class="form-control" placeholder="請輸入使用者姓名" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>登入帳號</label>
                        <input type="text" name="account" class="form-control" placeholder="請設定登入使用的帳號 (如: admin)" value="{{ old('account') }}" required>
                        <small class="form-text text-muted">此為登入時使用的識別碼。</small>
                    </div>

                    <div class="form-group">
                        <label>電子郵件 (選填)</label>
                        <input type="email" name="email" class="form-control" placeholder="請輸入聯絡用 Email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label>密碼</label>
                        <input type="password" name="password" class="form-control" placeholder="請輸入至少 4 位數密碼" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">建立帳號</button>
                    <a href="/users" class="btn btn-default">取消</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection