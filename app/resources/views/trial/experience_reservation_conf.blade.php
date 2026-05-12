@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- メインカード -->
    <div class="trial-newreg-card">

        <!-- カードタイトル -->
        <h2 class="trial-newreg-title">体験会予約確認</h2>

        <!-- 予約内容を表示（編集不可） -->
        <div class="trial-conf-field">
            <span class="trial-conf-label">日付</span>
            <span class="trial-conf-value">{{ $trialEvent->event_date->format('Y/n/j') }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">体験会時間</span>
            <span class="trial-conf-value">{{ $trialEvent->start_time }} 〜 {{ $trialEvent->end_time }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">コース名</span>
            <span class="trial-conf-value">{{ $trialEvent->course_name }}</span>
        </div>

        <!-- 予約確定フォーム -->
        <!-- 予約確定ボタンを押すと、選択した体験会IDを送信して予約が作成される -->
        <form method="POST" action="{{ route('trial.reservation.store', $inquiry) }}">
            @csrf
            <input type="hidden" name="trial_event_id" value="{{ $trialEvent->id }}">

            <!-- 予約確定ボタン -->
            <div class="trial-newreg-actions">
                <button type="submit" class="inqdetail-btn-submit">予約確定</button>
            </div>
        </form>

    </div>

</div>
@endsection
