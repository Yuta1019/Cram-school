@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- メインカード -->
    <div class="trial-newreg-card">

        <!-- カードタイトル -->
        <h2 class="trial-newreg-title">体験会実施日　情報編集</h2>

        <!-- 更新フォーム（id をつけてボタンから参照できるようにする） -->
        <form method="POST" action="{{ route('trial.confirmEdit', $trialEvent) }}" id="edit-form">
            @csrf

            <!-- 開催日 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">開催日</span>
                <input type="date" name="event_date"
                       value="{{ old('event_date', $trialEvent->event_date->format('Y-m-d')) }}"
                       class="trial-newreg-input @error('event_date') is-error @enderror">
                @error('event_date')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 体験会時間（開始〜終了） -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">体験会時間</span>
                <div class="trial-newreg-time-row">
                    <input type="time" name="start_time"
                           value="{{ old('start_time', $trialEvent->start_time) }}"
                           class="trial-newreg-input @error('start_time') is-error @enderror">
                    <span class="trial-newreg-time-sep">〜</span>
                    <input type="time" name="end_time"
                           value="{{ old('end_time', $trialEvent->end_time) }}"
                           class="trial-newreg-input @error('end_time') is-error @enderror">
                </div>
                @error('start_time')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
                @error('end_time')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- コース名 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">コース名</span>
                <select name="course_name"
                        class="trial-newreg-input @error('course_name') is-error @enderror">
                    <option value="">選択してください</option>
                    @foreach(['A', 'B', 'C'] as $course)
                        @if(old('course_name', $trialEvent->course_name) == $course)
                            <option value="{{ $course }}" selected>{{ $course }}</option>
                        @else
                            <option value="{{ $course }}">{{ $course }}</option>
                        @endif
                    @endforeach
                </select>
                @error('course_name')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 定員数 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">定員数</span>
                <input type="number" name="capacity"
                       value="{{ old('capacity', $trialEvent->capacity) }}"
                       min="0"
                       class="trial-newreg-input @error('capacity') is-error @enderror">
                @error('capacity')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

        </form>

        <!-- ボタンエリア（削除：左端　内容確認：右端） -->
        <div class="trial-edit-actions">
            <!-- 削除確認ページへ遷移するリンク -->
            <a href="{{ route('trial.confirmDelete', $trialEvent) }}" class="inqdetail-btn-delete">削除</a>
            <!-- form="edit-form" で更新フォームと紐づける -->
            <button type="submit" form="edit-form" class="inqdetail-btn-submit">内容確認</button>
        </div>

    </div>

</div>
@endsection
