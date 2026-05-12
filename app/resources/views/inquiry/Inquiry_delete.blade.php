@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">問い合わせ削除</h1>
    </div>

    <!-- タブ -->
    <div class="inqdetail-tabs">
        <button type="button" class="inqdetail-tab inqdetail-tab--active">基本情報</button>
        <button type="button" class="inqdetail-tab inqdetail-tab--disabled" disabled>連絡履歴</button>
        <button type="button" class="inqdetail-tab inqdetail-tab--disabled" disabled>所感</button>
    </div>

    <div class="inqdetail-body">

        <!-- 基本情報 -->
        <div class="inqdetail-left">
            <div class="inqdetail-left-header"></div>

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

        <!-- 問い合わせ内容・メモ＋ステータス -->
        <div class="inqdetail-right-col">

            <!-- 問い合わせ内容・メモパネル -->
            <div class="inqdetail-right">
                <h2 class="inqdetail-section-title">お問い合わせ内容/メモ</h2>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">お問い合わせ内容</span>
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

            <!-- ボタンエリア -->
            <div class="inqdetail-bottom-actions">

                <span class="inqdetail-delete-warning">顧客情報を削除しても良いですか？</span>

                <!-- 削除フォーム -->
                <form method="POST" action="{{ route('inquiry.destroy', $inquiry) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inqdetail-btn-delete">削除する</button>
                </form>

                <!-- 編集画面に戻るボタン -->
                <form method="GET" action="{{ route('inquiry.edit', $inquiry) }}">
                    <button type="submit" class="inqdetail-btn-cancel">編集画面に戻る</button>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection
