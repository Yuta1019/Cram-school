@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">お問い合わせ詳細 / 連絡履歴</h1>
    </div>

    <!-- タブ（連絡履歴がアクティブ） -->
    <div class="inqdetail-tabs">
        <a href="{{ route('inquiry.show', $inquiry) }}" class="inqdetail-tab">基本情報</a>
        <button type="button" class="inqdetail-tab inqdetail-tab--active">連絡履歴</button>
        <a href="{{ route('lesson_note.create', $inquiry) }}" class="inqdetail-tab">所感</a>
    </div>

    <!-- フラッシュメッセージ -->
    @if(session('success'))
        <div class="inq-flash-success">{{ session('success') }}</div>
    @endif

    <!-- サブタブ -->
    <div class="contact-subtabs">
        <button type="button" class="contact-subtab contact-subtab--active" id="tab-contact">連絡履歴</button>
        <button type="button" class="contact-subtab" id="tab-maillog">メール送信履歴</button>
    </div>

    <!-- 連絡履歴パネル -->
    <div id="panel-contact">

        <!-- 絞り込み検索＋履歴追加 -->
        <form method="GET" action="{{ route('contact.index', $inquiry) }}" class="inq-toolbar">

            <input type="date" name="contacted_at"
                   value="{{ request('contacted_at') }}"
                   class="inq-toolbar-input">

            <select name="contact_method" class="inq-toolbar-select">
                <option value="">手段：すべて</option>
                @foreach(['電話', 'メール', 'LINE', 'その他'] as $method)
                    <option value="{{ $method }}" {{ request('contact_method') == $method ? 'selected' : '' }}>
                        {{ $method }}
                    </option>
                @endforeach
            </select>

            <select name="contacted_by" class="inq-toolbar-select">
                <option value="">担当：すべて</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('contacted_by') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="inq-btn-search">検索</button>
            <a href="{{ route('contact.create', $inquiry) }}" class="inq-btn-new" style="margin-left: auto;">履歴追加</a>

        </form>

        <!-- 連絡履歴テーブル -->
        <div class="inq-table-card">
            @if($contactHistories->isEmpty())
                <p class="inq-empty">連絡履歴はありません。</p>
            @else
                <div class="inq-table-wrap">
                    <table class="inq-table">
                        <thead>
                            <tr>
                                <th>日付</th>
                                <th>手段</th>
                                <th>担当</th>
                                <th>内容</th>
                                <th>対応結果</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>操作</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contactHistories as $history)
                            <tr>
                                <td>
                                    @if($history->contacted_at)
                                        {{ $history->contacted_at->format('Y/n/j') }}
                                    @else
                                        －
                                    @endif
                                </td>
                                <td>
                                    @if($history->contact_method)
                                        {{ $history->contact_method }}
                                    @else
                                        －
                                    @endif
                                </td>
                                <td>
                                    @if($history->contactedBy)
                                        {{ $history->contactedBy->name }}
                                    @else
                                        －
                                    @endif
                                </td>
                                <td>
                                    @if($history->content)
                                        {{ $history->content }}
                                    @else
                                        －
                                    @endif
                                </td>
                                <td>
                                    @if($history->response_status == 1)
                                        対応完了
                                    @else
                                        返信待ち
                                    @endif
                                </td>
                                @if(auth()->user()->role === 'admin')
                                    <td>
                                        <button type="button"
                                                class="contact-delete-btn"
                                                data-id="{{ $history->id }}">
                                            削除
                                        </button>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div><!-- /#panel-contact -->

    <!-- メール送信履歴パネル -->
    <div id="panel-maillog" style="display:none;">
        <div class="inq-table-card">
            @if($mailLogs->isEmpty())
                <p class="inq-empty">送信履歴はありません。</p>
            @else
                <div class="inq-table-wrap">
                    <table class="inq-table">
                        <thead>
                            <tr>
                                <th>送信日時</th>
                                <th>送信者</th>
                                <th>件名</th>
                                <th>本文</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mailLogs as $log)
                            <tr>
                                <td class="maillog-date">{{ $log->created_at->format('Y/n/j H:i') }}</td>
                                <td>
                                    @if($log->sentBy)
                                        {{ $log->sentBy->name }}
                                    @else
                                        －
                                    @endif
                                </td>
                                <td>{{ $log->subject }}</td>
                                <td>
                                    <button type="button"
                                            class="maillog-body-btn"
                                            data-body="{{ e($log->body) }}">
                                        本文を見る
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div><!-- /#panel-maillog -->

</div>

<!-- メール本文モーダル -->
<div id="maillog-modal" class="maillog-modal-overlay" style="display:none;">
    <div class="maillog-modal-box">
        <div class="maillog-modal-header">
            <span class="maillog-modal-title">メール本文</span>
            <button type="button" id="maillog-modal-close" class="maillog-modal-close">✕</button>
        </div>
        <pre id="maillog-modal-body" class="maillog-modal-body"></pre>
    </div>
</div>

<!-- 削除確認モーダル -->
<div id="contact-delete-modal" class="contact-modal-overlay" style="display:none;">
    <div class="contact-modal-box">
        <p class="contact-modal-text">この連絡履歴を削除しますか？</p>
        <div class="contact-modal-actions">
            <form id="contact-delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="contact-modal-btn-yes">はい</button>
            </form>
            <button type="button" id="contact-modal-cancel" class="contact-modal-btn-no">いいえ</button>
        </div>
    </div>
</div>

<script>
// サブタブ切り替え
document.getElementById('tab-contact').addEventListener('click', function() {
    document.getElementById('panel-contact').style.display = '';
    document.getElementById('panel-maillog').style.display = 'none';
    document.getElementById('tab-contact').classList.add('contact-subtab--active');
    document.getElementById('tab-maillog').classList.remove('contact-subtab--active');
});

document.getElementById('tab-maillog').addEventListener('click', function() {
    document.getElementById('panel-maillog').style.display = '';
    document.getElementById('panel-contact').style.display = 'none';
    document.getElementById('tab-maillog').classList.add('contact-subtab--active');
    document.getElementById('tab-contact').classList.remove('contact-subtab--active');
});

// メール本文モーダル
document.querySelectorAll('.maillog-body-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.getElementById('maillog-modal-body').textContent = this.dataset.body;
        document.getElementById('maillog-modal').style.display = 'flex';
    });
});

document.getElementById('maillog-modal-close').addEventListener('click', function() {
    document.getElementById('maillog-modal').style.display = 'none';
});

document.getElementById('maillog-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});

// 連絡履歴削除ボタン
document.querySelectorAll('.contact-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var historyId = this.dataset.id;
        var deleteUrl = '/contacts/' + historyId;

        document.getElementById('contact-delete-form').action = deleteUrl;
        document.getElementById('contact-delete-modal').style.display = 'flex';
    });
});

document.getElementById('contact-modal-cancel').addEventListener('click', function() {
    document.getElementById('contact-delete-modal').style.display = 'none';
});

document.getElementById('contact-delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
    }
});
</script>

@endsection
