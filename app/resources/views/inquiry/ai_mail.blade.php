@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- ページタイトル -->
    <div class="inq-header">
        <h1 class="inq-title">AIメール作成</h1>
    </div>

    <!-- エラーメッセージ -->
    @if(session('error'))
        <div class="ai-mail-flash ai-mail-flash--error">{{ session('error') }}</div>
    @endif

    <!-- 左（顧客情報入力）+ 右（メール作成）の2カラムレイアウト -->
    <div class="ai-mail-body">

        <!-- 左：顧客情報入力 -->
        <div class="ai-mail-left">
            <h2 class="ai-mail-card-title">顧客情報を入力</h2>

            <!-- 保護者名 -->
            <div class="ai-mail-field">
                <span class="ai-mail-label">保護者名</span>
                <span class="ai-mail-value">{{ $inquiry->parent_name ?? '－' }}</span>
            </div>

            <!-- 生徒名 -->
            <div class="ai-mail-field">
                <span class="ai-mail-label">生徒名</span>
                <span class="ai-mail-value">{{ $inquiry->student_name ?? '－' }}</span>
            </div>

            <!-- 用途 -->
            <div class="ai-mail-field">
                <label class="ai-mail-label" for="purpose">用途</label>
                <input type="text" id="purpose" class="ai-mail-input" maxlength="200">
            </div>

            <!-- 文章を自動生成ボタン -->
            <button type="button" id="generate-btn" class="ai-mail-generate-btn">
                文章を自動生成
            </button>

            <!-- ローディング・エラー表示 -->
            <p id="generate-loading" class="ai-mail-loading" style="display:none;">生成中...</p>
            <p id="generate-error" class="ai-mail-error-msg" style="display:none;"></p>
        </div>

        <!-- 右：メール作成 -->
        <div class="ai-mail-right">
            <h2 class="ai-mail-card-title">メール作成</h2>

            <form method="POST" action="{{ route('ai_mail.send', $inquiry) }}">
                @csrf

                <!-- 件名 -->
                <div class="ai-mail-field">
                    <label class="ai-mail-label" for="subject">件名</label>
                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        class="ai-mail-input"
                        value="{{ old('subject') }}"
                        maxlength="200"
                    >
                    @error('subject')
                        <span class="ai-mail-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 本文 -->
                <div class="ai-mail-field">
                    <label class="ai-mail-label" for="body">本文</label>
                    <textarea id="body" name="body" class="ai-mail-textarea">{{ old('body') }}</textarea>
                    @error('body')
                        <span class="ai-mail-error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <!-- 添削・送信ボタン -->
                <div class="ai-mail-actions">
                    <button type="button" id="proofread-btn" class="ai-mail-proofread-btn">添削</button>
                    <button type="submit" class="ai-mail-send-btn">送信</button>
                </div>

                <!-- 添削中ローディング・エラー表示 -->
                <p id="proofread-loading" class="ai-mail-loading" style="display:none;">添削中...</p>
                <p id="proofread-error" class="ai-mail-error-msg" style="display:none;"></p>

            </form>
        </div>

    </div>
</div>

<script>

var csrfToken = '{{ csrf_token() }}';
var generateUrl = '{{ route('ai_mail.generate', $inquiry) }}';

// 生成・添削で共通のサーバー通信処理をまとめた関数
// sendData  ・・・ サーバーに送るデータ
// loadingId ・・・ 「生成中...」テキストのID
// errorId   ・・・ エラーメッセージのID
function callAI(sendData, loadingId, errorId) {
    var loadingEl = document.getElementById(loadingId);
    var errorEl   = document.getElementById(errorId);
    loadingEl.style.display = 'block';
    errorEl.style.display   = 'none';

    fetch(generateUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(sendData),
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
        loadingEl.style.display = 'none';
        if (data.text) {
            document.getElementById('body').value = data.text;
        } else {
            errorEl.textContent   = data.error || '失敗しました。もう一度お試しください。';
            errorEl.style.display = 'block';
        }
    })
    .catch(function () {
        loadingEl.style.display = 'none';
        errorEl.textContent     = '通信エラーが発生しました。もう一度お試しください。';
        errorEl.style.display   = 'block';
    });
}

// 文章を自動生成ボタン
document.getElementById('generate-btn').addEventListener('click', function () {
    var purpose = document.getElementById('purpose').value.trim();
    if (!purpose) {
        alert('用途を入力してから「文章を自動生成」を押してください。');
        return;
    }
    callAI(
        { type: 'generate', purpose: purpose },
        'generate-loading',
        'generate-error'
    );
});

// 添削ボタン
document.getElementById('proofread-btn').addEventListener('click', function () {
    var bodyText = document.getElementById('body').value.trim();
    if (!bodyText) {
        alert('本文を入力してから「添削」を押してください。');
        return;
    }
    callAI(
        { type: 'proofread', body: bodyText },
        'proofread-loading',
        'proofread-error'
    );
});
</script>
@endsection
