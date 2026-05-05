@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">お問い合わせ / 所感</h1>
    </div>

    <!-- タブ（所感がアクティブ） -->
    <div class="inqdetail-tabs">
        <a href="{{ route('inquiry.show', $inquiry) }}" class="inqdetail-tab">基本情報</a>
        @if(auth()->user()->role !== 'instructor')
            <a href="{{ route('contact.index', $inquiry) }}" class="inqdetail-tab">連絡履歴</a>
        @else
            <button type="button" class="inqdetail-tab inqdetail-tab--disabled" disabled>連絡履歴</button>
        @endif
        <button type="button" class="inqdetail-tab inqdetail-tab--active">所感</button>
    </div>

    <!-- 保存完了メッセージ -->
    @if(session('success'))
        <div class="impression-success-msg">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('lesson_note.store', $inquiry) }}">
        @csrf

        <div class="contact-form-card">

            <!-- コース名 -->
            <div class="impression-form-field">
                <span class="impression-form-label">コース名</span>
                <input type="text" name="course_name"
                       value="{{ old('course_name', $defaultCourseName) }}"
                       class="contact-form-input">
            </div>

            <!-- 授業日 -->
            <div class="impression-form-field">
                <span class="impression-form-label">授業日</span>
                <input type="date" name="lesson_date"
                       value="{{ old('lesson_date', $defaultLessonDate) }}"
                       class="contact-form-input @error('lesson_date') is-error @enderror">
                @error('lesson_date')
                    <span class="inqdetail-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- 理解度・集中度（横並び） -->
            <div class="impression-form-field">
                <div class="impression-level-row">

                    <div class="impression-level-item">
                        <span class="impression-level-label">理解度</span>
                        <select name="understanding_level" class="contact-form-select impression-level-select">
                            <option value="">選択してください</option>
                            @foreach($levelLabels as $value => $label)
                                @if(old('understanding_level', $defaultUnderstandingLevel) == $value && old('understanding_level', $defaultUnderstandingLevel) !== '')
                                    <option value="{{ $value }}" selected>{{ $label }}</option>
                                @else
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="impression-level-item">
                        <span class="impression-level-label">集中度</span>
                        <select name="concentration_level" class="contact-form-select impression-level-select">
                            <option value="">選択してください</option>
                            @foreach($levelLabels as $value => $label)
                                @if(old('concentration_level', $defaultConcentrationLevel) == $value && old('concentration_level', $defaultConcentrationLevel) !== '')
                                    <option value="{{ $value }}" selected>{{ $label }}</option>
                                @else
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <!-- 授業内容 -->
            <div class="impression-form-field">
                <span class="impression-form-label">授業内容</span>
                <textarea name="lesson_summary" class="contact-form-textarea" rows="4">{{ old('lesson_summary', $defaultLessonSummary) }}</textarea>
            </div>

            <!-- 保護者向けコメント -->
            <div class="impression-form-field">
                <span class="impression-form-label">保護者向けコメント</span>
                <textarea name="parent_comment" class="contact-form-textarea" rows="4">{{ old('parent_comment', $defaultParentComment) }}</textarea>
            </div>

            <!-- 講師メモ -->
            <div class="impression-form-field">
                <span class="impression-form-label">講師メモ</span>
                <textarea name="teacher_note" class="contact-form-textarea" rows="4">{{ old('teacher_note', $defaultTeacherNote) }}</textarea>
            </div>

        </div>

        <!-- 保存ボタン -->
        <div class="contact-form-actions">
            <button type="submit" class="inqdetail-btn-submit">保存</button>
        </div>

    </form>

</div>
@endsection
