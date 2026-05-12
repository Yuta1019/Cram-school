@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- メインカード -->
    <div class="trial-newreg-card">

        <!-- カードタイトル -->
        <h2 class="trial-newreg-title">体験会実施日　情報編集</h2>

        <!-- 確認用の表示エリア（編集はできない） -->
        <div class="trial-conf-field">
            <span class="trial-conf-label">開催日</span>
            <span class="trial-conf-value">{{ $inputData['event_date'] }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">体験会時間</span>
            <span class="trial-conf-value">{{ $inputData['start_time'] }} 〜 {{ $inputData['end_time'] }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">コース名</span>
            <span class="trial-conf-value">{{ $inputData['course_name'] }}</span>
        </div>

        <div class="trial-conf-field">
            <span class="trial-conf-label">定員数</span>
            <span class="trial-conf-value">{{ $inputData['capacity'] }} 名</span>
        </div>

        <!-- 保存フォーム（入力データをhiddenで持ち回す） -->
        <form method="POST" action="{{ route('trial.update', $trialEvent) }}">
            @csrf
            @method('PUT')

            <!-- 編集ページで入力した値を隠しフィールドとして持ち回す -->
            <input type="hidden" name="event_date"  value="{{ $inputData['event_date'] }}">
            <input type="hidden" name="start_time"  value="{{ $inputData['start_time'] }}">
            <input type="hidden" name="end_time"    value="{{ $inputData['end_time'] }}">
            <input type="hidden" name="course_name" value="{{ $inputData['course_name'] }}">
            <input type="hidden" name="capacity"    value="{{ $inputData['capacity'] }}">

            <!-- 保存ボタン -->
            <div class="trial-newreg-actions">
                <button type="submit" class="inqdetail-btn-submit">保存</button>
            </div>

        </form>

    </div>

</div>
@endsection
