@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- タイトル -->
    <div class="inq-header">
        <h1 class="inq-title">お問い合わせ一覧</h1>
    </div>

    <!-- 絞り込み検索バー -->
    <form method="GET" action="{{ route('inquiry.index') }}" class="inq-toolbar">
        <input
            type="text"
            name="student_name"
            value="{{ request('student_name') }}"
            placeholder="生徒名検索"
            class="inq-toolbar-input"
        >
        <input
            type="text"
            name="parent_name"
            value="{{ request('parent_name') }}"
            placeholder="保護者名"
            class="inq-toolbar-input"
        >
        <input
            type="text"
            name="course"
            value="{{ request('course') }}"
            placeholder="コース"
            class="inq-toolbar-input inq-toolbar-input--sm"
        >
        <select name="status" class="inq-toolbar-select">
            <option value="">状態：すべて</option>
            @foreach($statusLabels as $value => $label)
                <option value="{{ $value }}" {{ request('status') === (string)$value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="inq-btn-search">検索</button>
        <a href="{{ route('inquiry.create') }}" class="inq-btn-new">新規登録</a>
    </form>

    <!-- 顧客一覧テーブル -->
    <div class="inq-table-card">
        @if($inquiries->isEmpty())
            <p class="inq-empty">登録されている問い合わせはありません。</p>
        @else
            <div class="inq-table-wrap">
                <table class="inq-table">
                    <thead>
                        <tr>
                            <th>生徒名</th>
                            <th>保護者名</th>
                            <th>コース</th>
                            <th>状態</th>
                            <th>担当</th>
                            <th>最終対応</th>
                            <th>詳細</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inq)
                        <tr>
                            <td>{{ $inq->student_name }}</td>
                            <td>{{ $inq->parent_name }}</td>
                            <td>{{ $inq->desired_course_name ?? '－' }}</td>
                            <td>
                                <span class="inq-badge inq-badge-{{ $inq->status }}">
                                    {{ $inq->status_label }}
                                </span>
                            </td>
                            <td>{{ $inq->assignedUser ? $inq->assignedUser->name : '－' }}</td>
                            <td>
                                @if($inq->last_contact_at)
                                    {{ \Carbon\Carbon::parse($inq->last_contact_at)->format('n/j') }}
                                @else
                                    －
                                @endif
                            </td>
                            <td>
                                <a href="#" class="inq-open-btn">開く</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
