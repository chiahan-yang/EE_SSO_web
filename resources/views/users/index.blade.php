@extends('layouts.app')

@section('content')
<style>
    .table-responsive-custom {
        max-height: 600px; /* 設定上下滾動的最大高度 */
        overflow: auto;
    }
    /* 讓表頭在上下滾動時固定在頂部 */
    .table-responsive-custom thead th {
        position: sticky;
        top: 0;
        background-color: #f4f6f9;
        z-index: 1;
    }
</style>

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
            <div class="card-body p-0 table-responsive-custom">
                <table class="table table-hover table-striped text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>帳號類型</th>
                            <th>登入帳號</th>
                            <th>姓名</th>
                            <th>身分證號</th>
                            <th>群組</th>
                            <th>單位(單位代碼)</th>
                            <th>職稱(職稱代碼)</th>
                            <th>在職/學註記</th>
                            <th>Email</th>
                            <th>建立時間</th>
                            <th>更新時間</th>
                            <th style="width: 150px;">動作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                @if($user->user_type === 'sso')
                                    <span class="badge badge-info">SSO ({{ $user->pkind ?? '-' }})</span>
                                @else
                                    <span class="badge badge-secondary">本地</span>
                                @endif
                            </td>
                            <td><strong>{{ $user->account }}</strong></td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->idno ?? '-' }}</td>
                            <td>{{ $user->grpno ?? '-' }}</td>
                            <td>
                                {{ $user->dpt_desc1 ?? '-' }} 
                                @if($user->unicode1)
                                    <small class="text-muted">({{ $user->unicode1 }})</small>
                                @endif
                            </td>
                            <td>
                                {{ $user->title ?? '-' }}
                                @if($user->titcod)
                                    <small class="text-muted">({{ $user->titcod }})</small>
                                @endif
                            </td>
                            <td>
                                @if($user->leave == '0')
                                    <span class="text-success">在職/在學</span>
                                @elseif($user->leave == '1')
                                    <span class="text-danger">離職/畢掉業</span>
                                @else
                                    {{ $user->leave ?? '-' }}
                                @endif
                            </td>
                            <td>{{ $user->email ?? '-' }}</td>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $user->updated_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm" title="編輯資料">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($user->user_type === 'local')
                                        <button type="button" class="btn btn-warning btn-sm" onclick="openPasswordModal('{{ $user->id }}', '{{ $user->name }}')" title="修改密碼">
                                        <i class="fas fa-key"></i>
                                        </button>
                                    @endif

                                    @if(auth()->user()->account === $user->account)
                                        <button class="btn btn-default btn-sm" disabled title="無法刪除自己">
                                            <i class="fas fa-user-lock"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('確定要永久刪除此帳號嗎？');" style="display:inline;">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="刪除帳號">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card card-warning mb-0">
                <div class="card-header">
                    <h3 class="card-title">修改密碼 - <span id="modal_user_name"></span></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="passwordForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>請輸入新密碼</label>
                            <div class="input-group">
                                <input type="password" name="password" id="new_password" class="form-control" required minlength="4">
                                <div class="input-group-append" id="toggle_modal_password" style="cursor: pointer;">
                                    <div class="input-group-text">
                                        <span class="fas fa-eye" id="modal_eye_icon"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-default mr-2" data-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-warning">儲存新密碼</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 確保 openPasswordModal 定義在全域
    function openPasswordModal(id, name) {
        $('#modal_user_name').text(name);
        $('#passwordForm').attr('action', '/users/' + id + '/password'); 
        $('#passwordModal').modal('show');
    }

    $(document).ready(function() {
        // 彈窗內的眼睛切換功能
        $('#toggle_modal_password').on('click', function() {
            const field = $('#new_password');
            const icon = $('#modal_eye_icon');
            if (field.attr('type') === 'password') {
                field.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                field.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>
@endsection