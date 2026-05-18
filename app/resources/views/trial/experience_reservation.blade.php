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

            <!-- 定員オーバー時のエラーメッセージ -->
            @if(session('error'))
                <div class="trial-rsv-error">{{ session('error') }}</div>
            @endif

            @if($trialEvents->isEmpty())
                <!-- 体験会が登録されていないとき -->
                <p class="trial-rsv-empty">体験会の予定がありません。</p>
            @else
                <!-- 列ヘッダー（開催日・時間・コース・残り） -->
                <div class="trial-rsv-date-header">
                    <span>開催日</span>
                    <span>時間</span>
                    <span>コース</span>
                    <span>残り</span>
                </div>

                <!-- 体験会を1件ずつ表示する -->
                @foreach($trialEvents as $event)
                    @php
                        // 定員に達しているか判定する
                        $isFull = $event->reserved_count >= $event->capacity;
                        $remaining = $event->capacity - $event->reserved_count;
                    @endphp

                    @if($isFull)
                        <!-- 満員のときはボタンを押せないようにする -->
                        <div class="trial-rsv-date-btn trial-rsv-date-btn--full">
                            <span>{{ $event->event_date->format('Y/n/j') }}</span>
                            <span>{{ $event->start_time }} 〜 {{ $event->end_time }}</span>
                            <span>{{ $event->course_name }}</span>
                            <span class="trial-rsv-full-label">満員</span>
                        </div>
                    @else
                        <!-- 空きがある場合は選択できる -->
                        <form method="POST" action="{{ route('trial.reservation.confirm', $inquiry) }}">
                            @csrf
                            <input type="hidden" name="trial_event_id" value="{{ $event->id }}">
                            <button type="submit" class="trial-rsv-date-btn">
                                <span>{{ $event->event_date->format('Y/n/j') }}</span>
                                <span>{{ $event->start_time }} 〜 {{ $event->end_time }}</span>
                                <span>{{ $event->course_name }}</span>
                                <span>残り {{ $remaining }} 名</span>
                            </button>
                        </form>
                    @endif
                @endforeach
            @endif
        </div>

    </div>

</div>
@endsection
