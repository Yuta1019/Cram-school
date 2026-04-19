@extends('layouts.app')

@section('content')
<div class="login-page-body">
    <div class="login-page-wrapper">
        <main class="login-main-area">
            <div class="login-card">
                <div class="login-app-title">
                    パスワード再設定
                </div>

                @if ($errors->any())
                    <div class="login-error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="login-form-group">
                        <label for="email" class="login-label">メールアドレス</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="login-input"
                            value="{{ $email ?? old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <div class="login-form-group">
                        <label for="password" class="login-label">新しいパスワード</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="login-input"
                            required
                        >
                    </div>

                    <div class="login-form-group">
                        <label for="password_confirmation" class="login-label">新しいパスワード再度入力</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="login-input"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit-btn">
                        登録
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
