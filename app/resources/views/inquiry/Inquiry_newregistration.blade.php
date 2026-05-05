@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">問い合わせ新規登録</h1>
    </div>

    <form method="POST" action="{{ route('inquiry.store') }}">
        @csrf

        <div class="inqdetail-body">

            <!-- 基本情報 -->
            <div class="inqdetail-left">
                <h2 class="inqdetail-section-title">基本情報</h2>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">保護者者</span>
                    <div class="inqdetail-input-wrap">
                        <input type="text" name="parent_name" value="{{ old('parent_name') }}"
                               class="inqdetail-input @error('parent_name') is-error @enderror">
                        @error('parent_name')
                            <span class="inqdetail-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">電話</span>
                    <input type="text" name="parent_phone" value="{{ old('parent_phone') }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">メール</span>
                    <input type="email" name="parent_email" value="{{ old('parent_email') }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">生徒</span>
                    <div class="inqdetail-input-wrap">
                        <input type="text" name="student_name" value="{{ old('student_name') }}"
                               class="inqdetail-input @error('student_name') is-error @enderror">
                        @error('student_name')
                            <span class="inqdetail-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">学校</span>
                    <input type="text" name="school_name" value="{{ old('school_name') }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">学年</span>
                    <input type="text" name="grade" value="{{ old('grade') }}"
                           class="inqdetail-input">
                </div>
            </div>

            <!-- 問い合わせ内容・メモ -->
            <div class="inqdetail-right">
                <h2 class="inqdetail-section-title">問い合わせ内容 / メモ</h2>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">問い合わせ内容</span>
                    <textarea name="inquiry_content" class="inqdetail-textarea" rows="4">{{ old('inquiry_content') }}</textarea>
                </div>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">メモ</span>
                    <textarea name="memo" class="inqdetail-textarea" rows="3">{{ old('memo') }}</textarea>
                </div>

                <!-- 状態と希望コースを横並び -->
                <div class="inqdetail-row2">
                    <div class="inqdetail-right-field">
                        <span class="inqdetail-right-label">状態</span>
                        <select name="status" class="inqdetail-select">
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}"
                                    @if(old('status', 0) == $value)
                                        selected
                                    @endif
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inqdetail-right-field">
                        <span class="inqdetail-right-label">希望コース</span>
                        <select name="desired_course_name" class="inqdetail-select">
                            <option value="">未選択</option>
                            <option value="A"
                                @if(old('desired_course_name') == 'A')
                                    selected
                                @endif
                            >A</option>
                            <option value="B"
                                @if(old('desired_course_name') == 'B')
                                    selected
                                @endif
                            >B</option>
                            <option value="C"
                                @if(old('desired_course_name') == 'C')
                                    selected
                                @endif
                            >C</option>
                        </select>
                    </div>
                </div>

                <div class="inqdetail-actions">
                    <button type="submit" class="inqdetail-btn-submit">登録する</button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
