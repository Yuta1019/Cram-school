@extends('layouts.app')
@section('content')
<body class="login-page-body">
    <div class="login-page-wrapper">
        <main class="login-main-area">
            <div class="login-app-title">
                
            </div>

            <div class="login-card">
                <img src="{{ asset('images/キッズ向けプログラミング塾ロゴ+文字入り.png') }}" alt="プログラミング塾ロゴ" class="login-logo">

                @if (session('status'))
                    <div class="login-status-box">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="login-error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-form-group">
                        <label for="email" class="login-label">メールアドレス</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="login-input"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="login-form-group">
                        <label for="password" class="login-label">パスワード</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="login-input"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit-btn">
                        ログイン
                    </button>

                    <div class="login-sub-links">
                        <a href="{{ route('password.request') }}" class="login-sub-btn">
                            パスワードお忘れの方
                        </a>
                    </div>
                </form>
            </div>
              <a href="{{ route('register') }}" class="login-sub-links login-sub-btn">
                  新規会員登録はこちら
              </a>
        </main>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
@endsection