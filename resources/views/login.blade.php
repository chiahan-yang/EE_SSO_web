<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>電機系OO系統</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <p>國立中山大學</p><p>電機系OO系統</p>
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">使用SSO帳密登入</p>

      <form action="{{ url('/login') }}" method="post">
        {!! csrf_field() !!}
        <div class="input-group mb-3">
          <input type="text" name="account" class="form-control" value="{{ old('account') }}" placeholder="帳號 Account">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" name="password" id="login_password" class="form-control" placeholder="密碼 Password">
          <div class="input-group-append" id="toggle_login_password" style="cursor: pointer;">
            <div class="input-group-text">
              <span class="fas fa-eye" id="login_eye_icon"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="login_error">
              @include('layouts.ValidatorError')
            </div>
            <button type="submit" class="btn btn-primary btn-block">登入</button>
          </div>
          <div class="col-12">
            <p class="mb-1">
              <a href="https://sso.nsysu.edu.tw/" target="_blank"><span id="forget" name="forget">忘記密碼</span></a>
            </p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // 登入頁面密碼切換
    $('#toggle_login_password').click(function() {
        const passwordField = $('#login_password');
        const icon = $('#login_eye_icon');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
</body>
</html>
