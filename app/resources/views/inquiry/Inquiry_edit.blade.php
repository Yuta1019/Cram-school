@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">問い合わせ編集</h1>
    </div>

    <form method="POST" action="{{ route('inquiry.confirmEdit', $inquiry) }}">
        @csrf

        <div class="inqdetail-body">

            <!-- 基本情報 -->
            <div class="inqdetail-left">
                <h2 class="inqdetail-section-title">基本情報</h2>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">保護者名</span>
                    <div class="inqdetail-input-wrap">
                        <input type="text" name="parent_name"
                               value="{{ old('parent_name', $inquiry->parent_name) }}"
                               class="inqdetail-input @error('parent_name') is-error @enderror">
                        @error('parent_name')
                            <span class="inqdetail-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">電話</span>
                    <input type="text" name="parent_phone"
                           value="{{ old('parent_phone', $inquiry->parent_phone) }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">メール</span>
                    <input type="email" name="parent_email"
                           value="{{ old('parent_email', $inquiry->parent_email) }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">生徒名</span>
                    <div class="inqdetail-input-wrap">
                        <input type="text" name="student_name"
                               value="{{ old('student_name', $inquiry->student_name) }}"
                               class="inqdetail-input @error('student_name') is-error @enderror">
                        @error('student_name')
                            <span class="inqdetail-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">学校</span>
                    <input type="text" name="school_name"
                           value="{{ old('school_name', $inquiry->school_name) }}"
                           class="inqdetail-input">
                </div>

                <div class="inqdetail-field">
                    <span class="inqdetail-label">学年</span>
                    <input type="text" name="grade"
                           value="{{ old('grade', $inquiry->grade) }}"
                           class="inqdetail-input">
                </div>
            </div>

            <!-- 問い合わせ内容・メモ・各種選択 -->
            <div class="inqdetail-right">
                <h2 class="inqdetail-section-title">お問い合わせ内容 / メモ</h2>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">お問い合わせ内容</span>
                    <textarea name="inquiry_content" class="inqdetail-textarea" rows="4">{{ old('inquiry_content', $inquiry->inquiry_content) }}</textarea>
                </div>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">メモ</span>
                    <textarea name="memo" class="inqdetail-textarea" rows="3">{{ old('memo', $inquiry->memo) }}</textarea>
                </div>

                <div class="inqdetail-row2">

                    <div class="inqdetail-right-field">
                        <span class="inqdetail-right-label">状態</span>
                        <select name="status" class="inqdetail-select">
                            @foreach($statusLabels as $value => $label)
                                @if(old('status', $inquiry->status) == $value)
                                    <option value="{{ $value }}" selected>{{ $label }}</option>
                                @else
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="inqdetail-right-field">
                        <span class="inqdetail-right-label">希望コース</span>
                        <select name="desired_course_name" class="inqdetail-select">
                            <option value="">未選択</option>
                            @foreach(['A', 'B', 'C'] as $course)
                                @if(old('desired_course_name', $inquiry->desired_course_name) == $course)
                                    <option value="{{ $course }}" selected>{{ $course }}</option>
                                @else
                                    <option value="{{ $course }}">{{ $course }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">受付担当</span>
                    <select name="assigned_user_id" class="inqdetail-select">
                        <option value="">未選択</option>
                        @foreach($users as $user)
                            @if(old('assigned_user_id', $inquiry->assigned_user_id) == $user->id)
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @else
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="inqdetail-actions">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('inquiry.confirmDelete', $inquiry) }}" class="inqdetail-btn-delete">削除</a>
                    @endif
                    <button type="submit" class="inqdetail-btn-submit">内容確認</button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
