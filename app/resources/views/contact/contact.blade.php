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

    <!-- 絞り込み検索 -->
    <form method="GET" action="{{ route('contact.index', $inquiry) }}" class="inq-toolbar">

        <input type="date" name="contacted_at"
               value="{{ request('contacted_at') }}"
               class="inq-toolbar-input">

        <select name="contact_method" class="inq-toolbar-select">
            <option value="">手段：すべて</option>
            @foreach(['電話', 'メール', 'LINE', 'その他'] as $method)
                @if(request('contact_method') == $method)
                    <option value="{{ $method }}" selected>{{ $method }}</option>
                @else
                    <option value="{{ $method }}">{{ $method }}</option>
                @endif
            @endforeach
        </select>

        <select name="contacted_by" class="inq-toolbar-select">
            <option value="">担当：すべて</option>
            @foreach($users as $user)
                @if(request('contacted_by') == $user->id)
                    <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                @else
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endif
            @endforeach
        </select>

        <button type="submit" class="inq-btn-search">検索</button>

        <!-- 履歴追加ボタンを右端に配置 -->
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
