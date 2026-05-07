@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- タイトル -->
    <div class="trial-detail-header">
        <h1 class="trial-detail-title">詳細</h1>
        <!-- 受付・管理者のみ編集ボタンを表示 -->
        @if(auth()->user()->role !== 'instructor')
            <a href="#" class="trial-edit-btn">編集</a>
        @endif
    </div>

    <div class="trial-detail-body">

        <!-- 体験会実施日 -->
        <div class="trial-detail-card">
            <h2 class="trial-detail-card-title">体験会実施日</h2>

            <div class="trial-detail-field">
                <span class="trial-detail-label">開催日</span>
                <span class="trial-detail-value">
                    @if($trialEvent->event_date)
                        {{ $trialEvent->event_date->format('Y/n/j') }}
                    @else
                        －
                    @endif
                </span>
            </div>

            <div class="trial-detail-field">
                <span class="trial-detail-label">時間</span>
                <span class="trial-detail-value">
                    @if($trialEvent->start_time && $trialEvent->end_time)
                        {{ $trialEvent->start_time }} 〜 {{ $trialEvent->end_time }}
                    @else
                        －
                    @endif
                </span>
            </div>

            <div class="trial-detail-field">
                <span class="trial-detail-label">コース名</span>
                <span class="trial-detail-value">
                    @if($trialEvent->course_name)
                        {{ $trialEvent->course_name }}
                    @else
                        －
                    @endif
                </span>
            </div>

            <div class="trial-detail-field">
                <span class="trial-detail-label">定員</span>
                <span class="trial-detail-value">{{ $trialEvent->capacity }} 名</span>
            </div>

            <div class="trial-detail-field">
                <span class="trial-detail-label">予約数</span>
                <span class="trial-detail-value">{{ $trialEvent->reserved_count }} 名</span>
            </div>

            <div class="trial-detail-field">
                <span class="trial-detail-label">状態</span>
                <span class="trial-detail-value">
                    @if($trialEvent->status == 1)
                        満員
                    @else
                        空きあり
                    @endif
                </span>
            </div>

        </div>

        <!-- 参加生徒一覧 -->
        <div class="trial-detail-card">
            <h2 class="trial-detail-card-title">参加生徒一覧</h2>

            @if($reservations->isEmpty())
                <p class="trial-detail-empty">予約はありません。</p>
            @else
                <!-- 予約を1件ずつ表示する -->
                @foreach($reservations as $reservation)
                    <div class="trial-detail-field">
                        <span class="trial-detail-label">生徒名</span>
                        <span class="trial-detail-value">
                            @if($reservation->inquiry && $reservation->inquiry->student_name)
                                {{ $reservation->inquiry->student_name }}
                            @else
                                －
                            @endif
                        </span>
                    </div>
                    <div class="trial-detail-field">
                        <span class="trial-detail-label">予約状態</span>
                        <span class="trial-detail-value">{{ $reservation->reservation_status }}</span>
                    </div>
                    <hr class="trial-detail-divider">
                @endforeach
            @endif

        </div>

    </div>

</div>
@endsection
