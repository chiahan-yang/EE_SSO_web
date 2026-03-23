@extends('layouts.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>帳號管理 <a href="/users/create" class="btn btn-sm btn-primary float-right">新增本地帳號</a></h1>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>姓名</th><th>帳號(Email)</th><th>建立時間</th><th>動作</th></tr></thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('確定刪除？');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">刪除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection