@extends('layouts.app')

@section('content')
<div class="login-page-body">
    <div class="login-page-wrapper">
        <main class="login-main-area">

            <div class="login-card">
                @if($errors->any())
                    <div class="login-error-box">
                        @foreach($errors->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('signup.confirm') }}" method="POST">
                    @csrf

                <div class="login-app-title">
                    新規登録
                </div>

                    <div class="login-form-group">
                        <label for="role" class="login-label">役職選択</label>
                        <select id="role" name="role" class="login-input" style="height:46px;" required>
                            <option value="">選択してください</option>
                            <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>講師</option>
                            <option value="reception" {{ old('role') === 'reception' ? 'selected' : '' }}>受付</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>管理者</option>
                        </select>
                    </div>

                    <div class="login-form-group">
                        <label for="name" class="login-label">ユーザー名</label>
                        <input type="text" class="login-input" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="login-form-group">
                        <label for="email" class="login-label">メールアドレス</label>
                        <input type="email" class="login-input" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="login-form-group">
                        <label for="password" class="login-label">パスワード</label>
                        <input type="password" class="login-input" id="password" name="password" required>
                    </div>

                    <div class="login-form-group">
                        <label for="password-confirm" class="login-label">パスワード確認</label>
                        <input type="password" class="login-input" id="password-confirm" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="login-submit-btn">
                        入力確認
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection