@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- タイトル -->
    <div class="inq-header">
        <h1 class="inq-title">お問い合わせ詳細</h1>
    </div>

    <!-- タブ -->
    <div class="inqdetail-tabs">
        <button type="button" class="inqdetail-tab inqdetail-tab--active">基本情報</button>
        <button type="button" class="inqdetail-tab inqdetail-tab--disabled" disabled>連絡履歴</button>
        <button type="button" class="inqdetail-tab inqdetail-tab--disabled" disabled>所感</button>
    </div>

    <!-- 2カラムボディ -->
    <div class="inqdetail-body">

        <!-- 左：基本情報 -->
        <div class="inqdetail-left">
            <!-- 編集ボタン -->
            <div class="inqdetail-left-header">
                <a href="#" class="inqdetail-btn-edit">編集</a>
            </div>

            <div class="inqdetail-field">
                <span class="inqdetail-label">保護者名</span>
                <span class="inqdetail-value">
                    @if($inquiry->parent_name)
                        {{ $inquiry->parent_name }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">電話</span>
                <span class="inqdetail-value">
                    @if($inquiry->parent_phone)
                        {{ $inquiry->parent_phone }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">メール</span>
                <span class="inqdetail-value">
                    @if($inquiry->parent_email)
                        {{ $inquiry->parent_email }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">生徒名</span>
                <span class="inqdetail-value">
                    @if($inquiry->student_name)
                        {{ $inquiry->student_name }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">学校</span>
                <span class="inqdetail-value">
                    @if($inquiry->school_name)
                        {{ $inquiry->school_name }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">学年</span>
                <span class="inqdetail-value">
                    @if($inquiry->grade)
                        {{ $inquiry->grade }}
                    @else
                        －
                    @endif
                </span>
            </div>
        </div>

        <!-- 右：問い合わせ内容・メモ＋ステータス -->
        <div class="inqdetail-right-col">

            <!-- 問い合わせ内容・メモパネル -->
            <div class="inqdetail-right">
                <h2 class="inqdetail-section-title">問い合わせ内容/メモ</h2>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">問い合わせ内容</span>
                    <p class="inqdetail-text">
                        @if($inquiry->inquiry_content)
                            {{ $inquiry->inquiry_content }}
                        @else
                            －
                        @endif
                    </p>
                </div>
                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">メモ</span>
                    <p class="inqdetail-text">
                        @if($inquiry->memo)
                            {{ $inquiry->memo }}
                        @else
                            －
                        @endif
                    </p>
                </div>
            </div>

            <!-- ステータスパネル -->
            <div class="inqdetail-status-panel">
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">入会状況</span>
                    <span class="inq-badge inq-badge-{{ $inquiry->status }}">
                        {{ $inquiry->status_label }}
                    </span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">希望コース</span>
                    <span class="inqdetail-value">
                        @if($inquiry->desired_course_name)
                            {{ $inquiry->desired_course_name }}
                        @else
                            －
                        @endif
                    </span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">受付担当</span>
                    <span class="inqdetail-value">
                        @if($inquiry->assignedUser)
                            {{ $inquiry->assignedUser->name }}
                        @else
                            －
                        @endif
                    </span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">作成日</span>
                    <span class="inqdetail-value">{{ $inquiry->created_at->format('Y/n/j') }}</span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">更新日</span>
                    <span class="inqdetail-value">{{ $inquiry->updated_at->format('Y/n/j') }}</span>
                </div>
            </div>

            <!-- アクションボタン -->
            <div class="inqdetail-bottom-actions">
                <a href="#" class="inqdetail-btn-trial">体験会予約へ進む</a>
                <a href="#" class="inqdetail-btn-ai">AIメール</a>
            </div>

        </div>
    </div>

</div>
@endsection
