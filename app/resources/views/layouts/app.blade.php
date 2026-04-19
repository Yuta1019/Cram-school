<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand mb-0" href="{{ url('/') }}">
                    <img src="{{ asset('images/キッズ向けプログラミング塾ロゴ+文字入り.png') }}" alt="プログラミング塾ロゴ" width="200" height="auto">
                </a>

                <div class="d-flex align-items-center gap-3">
                    @if(Auth::check())
                        <span class="mb-0">{{ Auth::user()->name }}</span>
                        <a href="#" id="logout" class="text-decoration-none">ログアウト</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <script>
                            document.getElementById('logout').addEventListener('click', function(event) {
                                event.preventDefault();
                                document.getElementById('logout-form').submit();
                            });
                        </script>
                    
                    @endif
                </div>
            </div>
        </nav>

        <div class="login-page-body">
            @yield('content')
        </div>
    </div>
</body>
</html>
