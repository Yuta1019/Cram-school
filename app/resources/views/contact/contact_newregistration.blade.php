<!-- layouts/main.blade.php（ヘッダー・サイドバー）を読み込む -->
@extends('layouts.main')

@section('content')
<div class="inq-page">

    <div class="inq-header">
        <h1 class="inq-title">連絡履歴登録</h1>
    </div>

    <!-- フォーム：送信先は ContactController@store -->
    <form method="POST" action="{{ route('contact.store', $inquiry) }}">

        @csrf
        <div class="contact-form-card">

            <!-- 日付入力 -->
            <div class="contact-form-field">
                <span class="contact-form-label">日付</span>
                <div class="contact-form-input-wrap">
                    <!-- type="datetime-local" で日付と時刻を入力 -->
                    <input type="datetime-local" name="contacted_at"
                           value="{{ old('contacted_at') }}"
                           class="contact-form-input @error('contacted_at') is-error @enderror">
                    <!-- バリデーションエラーがあれば赤字で表示 -->
                    @error('contacted_at')
                        <span class="inqdetail-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- 連絡手段の選択 -->
            <div class="contact-form-field">
                <span class="contact-form-label">手段</span>
                <div class="contact-form-input-wrap">
                    <select name="contact_method"
                            class="contact-form-select @error('contact_method') is-error @enderror">
                        <option value="">選択してください</option>

                        <!-- 選択肢を1つずつ表示し、前回選んだものを selected にする -->
                        @foreach(['電話', 'メール', 'LINE', 'その他'] as $method)
                            @if(old('contact_method') == $method)
                                <option value="{{ $method }}" selected>{{ $method }}</option>
                            @else
                                <option value="{{ $method }}">{{ $method }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('contact_method')
                        <span class="inqdetail-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- 担当者の選択 -->
            <div class="contact-form-field">
                <span class="contact-form-label">担当</span>
                <select name="contacted_by" class="contact-form-select">
                    <option value="">選択してください</option>

                    <!-- コントローラーから渡された $users を1人ずつ表示 -->
                    @foreach($users as $user)
                        @if(old('contacted_by') == $user->id)
                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                        @else
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- 連絡内容のテキスト入力 -->
            <div class="contact-form-field">
                <span class="contact-form-label">内容</span>
                <textarea name="content" class="contact-form-textarea" rows="4">{{ old('content') }}</textarea>
            </div>

            <!-- 対応状態の選択 -->
            <div class="contact-form-field">
                <span class="contact-form-label">状態</span>
                <select name="response_status" class="contact-form-select">
                    <!-- ContactHistory::RESPONSE_STATUS_LABELS から選択肢を表示 -->
                    @foreach($responseStatusLabels as $value => $label)
                        @if(old('response_status', 0) == $value)
                            <option value="{{ $value }}" selected>{{ $label }}</option>
                        @else
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>

        <!-- 登録ボタン -->
        <div class="contact-form-actions">
            <button type="submit" class="inqdetail-btn-submit">登録</button>
        </div>

    </form>

</div>
@endsection
