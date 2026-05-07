<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>プログラミング塾</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @yield('styles')
</head>
<body class="main-page-body">

<!-- トップヘッダー -->
<header class="main-header">
    <div class="main-header-inner">
        <a href="{{ route('inquiry.index') }}" class="main-header-logo">
            <img src="{{ asset('images/キッズ向けプログラミング塾ロゴ+文字入り.png') }}" alt="プログラミング塾">
        </a>
        @auth
        <div class="main-header-right">
            <span class="main-header-username">{{ Auth::user()->name }}</span>
            <span class="main-header-sep">/</span>
            <a href="#" id="logout-link" class="main-header-logout">ログアウト</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
        @endauth
    </div>
</header>

<div class="main-container">

    <!-- サイドナビゲーション -->
    <aside class="main-sidebar">
        <nav class="main-sidebar-nav">
            <a href="{{ route('inquiry.index') }}"
               class="main-sidebar-item {{ request()->routeIs('inquiry.*') ? 'active' : '' }}">
                お問い合わせ
            </a>
            <a href="{{ route('trial.index') }}"
               class="main-sidebar-item {{ request()->routeIs('trial.*') ? 'active' : '' }}">
                体験会一覧
            </a>
            <a href="#" class="main-sidebar-item">AIメール</a>
            <a href="#" class="main-sidebar-item">設定</a>
        </nav>
    </aside>

    <!-- メインコンテンツ -->
    <main class="main-content">
        @yield('content')
    </main>

</div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    var logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });
    }
</script>
@yield('scripts')
</body>
</html>
