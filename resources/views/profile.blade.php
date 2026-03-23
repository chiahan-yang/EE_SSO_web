@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">個人資訊 / SSO 偵錯</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                        </div>
                        <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                        <p class="text-muted text-center">{{ Auth::user()->email }}</p>
                        <p class="text-center">
                            <span class="badge {{ session('sso_info') ? 'badge-success' : 'badge-warning' }}">
                                {{ session('sso_info') ? 'SSO 登入' : '本地帳號登入' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">SSO 回傳原始資料 (偵錯用)</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <tbody>
                                @if(session('sso_info'))
                                    @foreach(session('sso_info') as $key => $value)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td class="text-primary font-weight-bold">{{ $value }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">
                                            無 SSO 資料 (您可能是使用本地帳號登入)
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection