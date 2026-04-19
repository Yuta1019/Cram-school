@extends('layouts.app')

@section('content')
<div class="login-page-body">
    <div class="login-page-wrapper">
        <main class="login-main-area">
            <div class="login-card">
                <div class="login-app-title">
                    新規登録内容確認
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <input type="hidden" name="role" value="{{ $data['role'] }}">
                    <input type="hidden" name="name" value="{{ $data['name'] }}">
                    <input type="hidden" name="email" value="{{ $data['email'] }}">
                    <input type="hidden" name="password" value="{{ $data['password'] }}">
                    <input type="hidden" name="password_confirmation" value="{{ $data['password'] }}">

                    <div class="login-form-group">
                        <label class="login-label">役職</label>
                        <div class="login-confirm-value">{{ $data['role'] === 'teacher' ? '講師' : ($data['role'] === 'reception' ? '受付' : '管理者') }}</div>
                    </div>

                    <div class="login-form-group">
                        <label class="login-label">ユーザー名</label>
                        <div class="login-confirm-value">{{ $data['name'] }}</div>
                    </div>

                    <div class="login-form-group">
                        <label class="login-label">メールアドレス</label>
                        <div class="login-confirm-value">{{ $data['email'] }}</div>
                    </div>

                    <div class="login-form-group">
                        <label class="login-label">パスワード</label>
                        <div class="login-confirm-value">●●●●●●●●</div>
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