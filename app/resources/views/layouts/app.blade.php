<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand mb-0" href="{{ url('/') }}">
                    家計簿
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
                    @else
                        <a class="text-decoration-none" href="{{ route('login') }}">ログイン</a>
                        <a class="text-decoration-none" href="{{ route('register') }}">会員登録</a>
                    @endif
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
</body>
</html>
