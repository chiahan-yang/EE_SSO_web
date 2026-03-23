@extends('layouts.app')
@section('content')
<div class="content-header"><div class="container-fluid"><h1>新增本地帳號</h1></div></div>
<div class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <form action="/users" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group"><label>姓名</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label>帳號 (Email)</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label>密碼</label><input type="text" name="password" class="form-control" required></div>
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