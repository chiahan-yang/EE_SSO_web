@extends('layouts.app') {{-- 繼承 resources/views/layouts/app.blade.php --}}

@section('content')
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">首頁</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="m-0">歡迎使用</h5>
              </div>
              <div class="card-body">
                <h6 class="card-title">你好，{{ Auth::user()->name }}！</h6>
                <p class="card-text">
                  您已成功登入系統。這裡可以放一些公告事項或是今日課表概覽。
                </p>
                <a href="#" class="btn btn-primary">查看預約狀況</a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
@endsection