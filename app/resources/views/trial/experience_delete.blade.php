@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- メインカード -->
    <div class="trial-newreg-card">

        <!-- カードタイトル -->
        <h2 class="trial-newreg-title">体験会実施日　削除確認</h2>

        <!-- 削除対象の体験会データを表示（編集不可） -->
        <div class="trial-conf-field">
            <span class="trial-conf-label">開催日</span>
            <span class="trial-conf-value">{{ $trialEvent->event_date->format('Y年m月d日') }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">体験会時間</span>
            <span class="trial-conf-value">{{ $trialEvent->start_time }} 〜 {{ $trialEvent->end_time }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">コース名</span>
            <span class="trial-conf-value">{{ $trialEvent->course_name }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">定員数</span>
            <span class="trial-conf-value">{{ $trialEvent->capacity }} 名</span>
        </div>

        <!-- 削除フォーム（ボタンエリアの削除ボタンから参照する） -->
        <form method="POST" action="{{ route('trial.destroy', $trialEvent) }}" id="delete-form">
            @csrf
            @method('DELETE')
        </form>

        <!-- ボタンエリア（警告テキスト：左端　ボタン類：右端） -->
        <div class="trial-delete-actions">
            <!-- 削除を促す警告テキスト -->
            <span class="trial-delete-warning">体験会実施日の実施を削除しますか？</span>

            <!-- ボタン類 -->
            <div class="trial-delete-btns">
                <!-- 編集画面に戻るリンク -->
                <a href="{{ route('trial.edit', $trialEvent) }}" class="inqdetail-btn-back">編集画面に戻る</a>
                <!-- form="delete-form" で削除フォームと紐づける -->
                <button type="submit" form="delete-form" class="inqdetail-btn-delete">削除</button>
            </div>
        </div>

    </div>

</div>
@endsection
