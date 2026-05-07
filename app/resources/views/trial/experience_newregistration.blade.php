@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- メインカード -->
    <div class="trial-newreg-card">

        <!-- カードタイトル -->
        <h2 class="trial-newreg-title">体験会実施日　新規登録</h2>

        <form method="POST" action="{{ route('trial.store') }}">
            @csrf

            <!-- 開催日入力 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">開催日</span>
                <input type="date" name="event_date"
                       value="{{ old('event_date') }}"
                       placeholder="開催日入力"
                       class="trial-newreg-input @error('event_date') is-error @enderror">
                @error('event_date')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 体験会時間入力 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">体験会時間</span>
                <div class="trial-newreg-time-row">
                    <input type="time" name="start_time"
                           value="{{ old('start_time') }}"
                           placeholder="開始時間"
                           class="trial-newreg-input @error('start_time') is-error @enderror">
                    <span class="trial-newreg-time-sep">〜</span>
                    <input type="time" name="end_time"
                           value="{{ old('end_time') }}"
                           placeholder="終了時間"
                           class="trial-newreg-input @error('end_time') is-error @enderror">
                </div>
                @error('start_time')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
                @error('end_time')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- コース名選択 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">コース名</span>
                <select name="course_name"
                        class="trial-newreg-input @error('course_name') is-error @enderror">
                    <option value="">選択してください</option>
                    @foreach(['A', 'B', 'C'] as $course)
                        @if(old('course_name') == $course)
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

            <!-- 定員数入力 -->
            <div class="trial-newreg-field">
                <span class="trial-newreg-label">定員数</span>
                <input type="number" name="capacity"
                       value="{{ old('capacity', 0) }}"
                       placeholder="定員数入力"
                       min="0"
                       class="trial-newreg-input @error('capacity') is-error @enderror">
                @error('capacity')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 登録ボタン -->
            <div class="trial-newreg-actions">
                <button type="submit" class="inqdetail-btn-submit">登録</button>
            </div>

        </form>

    </div>

</div>
@endsection
