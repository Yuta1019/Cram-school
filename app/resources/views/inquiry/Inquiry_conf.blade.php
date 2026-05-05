@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">内容確認</h1>
    </div>

    <!-- 確認表示 -->
    <div class="inqdetail-body">

        <!-- 基本情報 -->
        <div class="inqdetail-left">
            <div class="inqdetail-left-header"></div>

            <div class="inqdetail-field">
                <span class="inqdetail-label">保護者名</span>
                <span class="inqdetail-value">
                    @if($inputData['parent_name'])
                        {{ $inputData['parent_name'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">電話</span>
                <span class="inqdetail-value">
                    @if($inputData['parent_phone'])
                        {{ $inputData['parent_phone'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">メール</span>
                <span class="inqdetail-value">
                    @if($inputData['parent_email'])
                        {{ $inputData['parent_email'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">生徒名</span>
                <span class="inqdetail-value">
                    @if($inputData['student_name'])
                        {{ $inputData['student_name'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">学校</span>
                <span class="inqdetail-value">
                    @if($inputData['school_name'])
                        {{ $inputData['school_name'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
            <div class="inqdetail-field">
                <span class="inqdetail-label">学年</span>
                <span class="inqdetail-value">
                    @if($inputData['grade'])
                        {{ $inputData['grade'] }}
                    @else
                        －
                    @endif
                </span>
            </div>
        </div>

        <!-- 問い合わせ内容・メモ・ステータス -->
        <div class="inqdetail-right-col">

            <div class="inqdetail-right">
                <h2 class="inqdetail-section-title">お問い合わせ内容 / メモ</h2>

                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">お問い合わせ内容</span>
                    <p class="inqdetail-text">
                        @if($inputData['inquiry_content'])
                            {{ $inputData['inquiry_content'] }}
                        @else
                            －
                        @endif
                    </p>
                </div>
                <div class="inqdetail-right-field">
                    <span class="inqdetail-right-label">メモ</span>
                    <p class="inqdetail-text">
                        @if($inputData['memo'])
                            {{ $inputData['memo'] }}
                        @else
                            －
                        @endif
                    </p>
                </div>
            </div>

            <div class="inqdetail-status-panel">
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">入会状況</span>
                    <span class="inqdetail-value">{{ $statusLabel }}</span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">希望コース</span>
                    <span class="inqdetail-value">
                        @if($inputData['desired_course_name'])
                            {{ $inputData['desired_course_name'] }}
                        @else
                            －
                        @endif
                    </span>
                </div>
                <div class="inqdetail-status-row">
                    <span class="inqdetail-status-label">受付担当</span>
                    <span class="inqdetail-value">{{ $assignedUserName }}</span>
                </div>
            </div>

            <!-- ボタンエリア -->
            <div class="inqdetail-bottom-actions">

                <!-- 修正するボタン -->
                <form method="GET" action="{{ route('inquiry.edit', $inquiry) }}">
                    <button type="submit" class="inqdetail-btn-cancel">修正する</button>
                </form>

                <!-- 保存するフォーム -->
                <form method="POST" action="{{ route('inquiry.update', $inquiry) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="parent_name"         value="{{ $inputData['parent_name'] }}">
                    <input type="hidden" name="parent_phone"        value="{{ $inputData['parent_phone'] }}">
                    <input type="hidden" name="parent_email"        value="{{ $inputData['parent_email'] }}">
                    <input type="hidden" name="student_name"        value="{{ $inputData['student_name'] }}">
                    <input type="hidden" name="school_name"         value="{{ $inputData['school_name'] }}">
                    <input type="hidden" name="grade"               value="{{ $inputData['grade'] }}">
                    <input type="hidden" name="desired_course_name" value="{{ $inputData['desired_course_name'] }}">
                    <input type="hidden" name="inquiry_content"     value="{{ $inputData['inquiry_content'] }}">
                    <input type="hidden" name="status"              value="{{ $inputData['status'] }}">
                    <input type="hidden" name="assigned_user_id"    value="{{ $inputData['assigned_user_id'] }}">
                    <input type="hidden" name="memo"                value="{{ $inputData['memo'] }}">

                    <button type="submit" class="inqdetail-btn-submit">保存する</button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
