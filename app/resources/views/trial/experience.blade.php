@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- ページタイトル -->
    <div class="inq-header">
        <h1 class="inq-title">体験会</h1>
    </div>

    <!-- メインカード -->
    <div class="trial-card">

        <!-- 一覧・カレンダー タブ -->
        <div class="trial-tabs">
            <button type="button" class="trial-tab trial-tab--active">一覧</button>
            <a href="{{ route('trial.calendar') }}" class="trial-tab">カレンダー</a>
        </div>

        <!-- 体験会テーブル -->
        @if($trialEvents->isEmpty())
            <p class="inq-empty">体験会はありません。</p>
        @else
            <table class="trial-table">
                <thead>
                    <tr>
                        <th>開催日</th>
                        <th>時間</th>
                        <th>コース</th>
                        <th>定員</th>
                        <th>詳細</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 体験会を1件ずつ表示する -->
                    @foreach($trialEvents as $event)
                    <tr>
                        <!-- 開催日 -->
                        <td>
                            @if($event->event_date)
                                {{ $event->event_date->format('Y/n/j') }}
                            @else
                                －
                            @endif
                        </td>

                        <!-- 時間（開始〜終了） -->
                        <td>
                            @if($event->start_time && $event->end_time)
                                {{ $event->start_time }} 〜 {{ $event->end_time }}
                            @else
                                －
                            @endif
                        </td>

                        <!-- コース名 -->
                        <td>
                            @if($event->course_name)
                                {{ $event->course_name }}
                            @else
                                －
                            @endif
                        </td>

                        <!-- 定員 -->
                        <td>
                            {{ $event->capacity }}
                        </td>

                        <!-- 詳細ページへのリンク -->
                        <td>
                            <a href="{{ route('trial.show', $event) }}" class="trial-open-btn">開く</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>

    <!-- 開催日登録ボタン -->
    <div class="trial-register-area">
        @if(auth()->user()->role !== 'instructor')
            <a href="{{ route('trial.create') }}" class="trial-register-btn">開催日登録</a>
        @endif
    </div>

</div>
@endsection
