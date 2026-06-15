@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">AIメール作成</h1>
    </div>

    <div class="ai-mail-auth-wrap">
        <div class="ai-mail-auth-card">

            <h2 class="ai-mail-auth-title">パスワード確認</h2>
            <p class="ai-mail-auth-desc">AIメール機能を利用するには、パスワードを入力してください。</p>

            @if(session('auth_error'))
                <div class="ai-mail-auth-error">{{ session('auth_error') }}</div>
            @endif

            <form method="POST" action="{{ route('ai_mail.verify', $inquiry) }}">
                @csrf

                <div class="ai-mail-auth-field">
                    <label class="ai-mail-auth-label" for="password">パスワード</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="ai-mail-auth-input"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="ai-mail-auth-btn">確認</button>

            </form>

            <a href="{{ route('inquiry.show', $inquiry) }}" class="ai-mail-auth-back">← 詳細ページへ戻る</a>

        </div>
    </div>

</div>
@endsection
