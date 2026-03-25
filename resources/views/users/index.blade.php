@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1>
            帳號管理 
            <a href="/users/create" class="btn btn-sm btn-primary float-right">
                <i class="fas fa-plus"></i> 新增本地帳號
            </a>
        </h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>類型</th>
                            <th>登入帳號</th>
                            <th>姓名</th>
                            <th>Email</th>
                            <th>建立時間</th>
                            <th>最後更新</th>
                            <th style="width: 100px;">動作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                @if($user->user_type === 'sso')
                                    <span class="badge badge-info">SSO 會員</span>
                                @else
                                    <span class="badge badge-secondary">本地帳號</span>
                                @endif
                            </td>
                            
                            <td><strong>{{ $user->account }}</strong></td>
                            
                            <td>{{ $user->name }}</td>
                            
                            <td>{{ $user->email ?? '-' }}</td>
                            
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>

                            <td>
                                @if(auth()->user()->account === $user->account)
                                    <button class="btn btn-default btn-sm" disabled title="無法刪除自己">
                                        <i class="fas fa-user-lock"></i> 本人
                                    </button>
                                @else
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
                                        onsubmit="return confirm('確定要永久刪除此帳號（{{ $user->name }}）嗎？');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> 刪除
                                        </button>
                                    </form>
                                @endif
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