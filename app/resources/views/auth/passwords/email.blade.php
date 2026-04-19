@extends('layouts.app')

@section('content')
<div class="login-page-body">
    <div class="login-page-wrapper">
        <main class="login-main-area">
            <div class="login-card">
                <div class="login-app-title">
                    パスワード再設定
                </div>

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

                <form method="POST" action="{{ route('password.email') }}">
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

                    <button type="submit" class="login-submit-btn">
                        メール送信
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
