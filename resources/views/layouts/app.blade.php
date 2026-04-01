<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>電機系行政系統</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
            {{ Auth::user()->name ?? '使用者' }}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt mr-2"></i> 登出
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/home" class="brand-link">
      <span class="brand-text font-weight-light">電機系行政系統</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
    
    <li class="nav-item">
        <a href="/home" class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>首頁概覽</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="/profile" class="nav-link">
            <i class="nav-icon fas fa-id-card"></i>
            <p>個人資訊 / SSO 資訊</p>
        </a>
    </li>

    <li class="nav-item">
        <a href="/users" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>帳號管理</p>
        </a>
    </li>

</ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    @yield('content')
  </div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Ver 1.0
    </div>
    <strong>Copyright &copy; 2025 <a href="https://ee.nsysu.edu.tw">NSYSU EE</a>.</strong>
  </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@yield('scripts')

</body>
</html>