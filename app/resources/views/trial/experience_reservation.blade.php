@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- ページタイトル -->
    <div class="inq-header">
        <h1 class="inq-title">体験会予約</h1>
    </div>

    <!-- 顧客情報（左）+ 日付選択（右）の2カラムレイアウト -->
    <div class="trial-rsv-body">

        <!-- 左：顧客情報カード -->
        <div class="trial-rsv-customer">
            <h2 class="trial-rsv-card-title">顧客情報</h2>

            <!-- 保護者名 -->
            <div class="trial-rsv-field">
                <span class="trial-rsv-label">保護者名</span>
                <span class="trial-rsv-value">
                    {{ $inquiry->parent_name ?? '－' }}
                </span>
            </div>

            <!-- 生徒名 -->
            <div class="trial-rsv-field">
                <span class="trial-rsv-label">生徒名</span>
                <span class="trial-rsv-value">
                    {{ $inquiry->student_name ?? '－' }}
                </span>
            </div>

            <!-- 希望コース名 -->
            <div class="trial-rsv-field">
                <span class="trial-rsv-label">希望コース名</span>
                <span class="trial-rsv-value">
                    {{ $inquiry->desired_course_name ?? '－' }}
                </span>
            </div>
        </div>

        <!-- 右：日付選択カード -->
        <div class="trial-rsv-dates">
            <h2 class="trial-rsv-card-title">日付選択</h2>

            @if($trialEvents->isEmpty())
                <!-- 体験会が登録されていないとき -->
                <p class="trial-rsv-empty">体験会の予定がありません。</p>
            @else
                <!-- 列ヘッダー（開催日・時間・コース・定員） -->
                <div class="trial-rsv-date-header">
                    <span>開催日</span>
                    <span>時間</span>
                    <span>コース</span>
                    <span>定員</span>
                </div>

                <!-- 体験会を1件ずつボタンとして表示する -->
                <!-- ボタンを押すと、その体験会で予約が作成される -->
                @foreach($trialEvents as $event)
                    <form method="POST" action="{{ route('trial.reservation.confirm', $inquiry) }}">
                        @csrf
                        <!-- 選択した体験会のIDを送信する -->
                        <input type="hidden" name="trial_event_id" value="{{ $event->id }}">
                        <button type="submit" class="trial-rsv-date-btn">
                            <span>{{ $event->event_date->format('Y/n/j') }}</span>
                            <span>{{ $event->start_time }} 〜 {{ $event->end_time }}</span>
                            <span>{{ $event->course_name }}</span>
                            <span>{{ $event->capacity }} 名</span>
                        </button>
                    </form>
                @endforeach
            @endif
        </div>

    </div>

</div>
@endsection
