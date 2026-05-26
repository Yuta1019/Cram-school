@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- ページタイトル -->
    <div class="inq-header">
        <h1 class="inq-title">設定</h1>
    </div>

    <!-- フラッシュメッセージ -->
    @if(session('success'))
        <div class="setting-flash setting-flash--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="setting-flash setting-flash--error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="setting-flash setting-flash--error">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <!-- CSVの入出力カード -->
    <div class="setting-card">
        <h2 class="setting-card-title">CSVの入出力</h2>

        <form method="POST" action="{{ route('setting.import') }}" enctype="multipart/form-data">
            @csrf

            <!-- データ種別の選択 -->
            <div class="setting-type-row">
                <button type="button" class="setting-type-btn active" data-type="inquiry">
                    お問い合わせ
                </button>
                <button type="button" class="setting-type-btn" data-type="trial">
                    体験会情報
                </button>
                <!-- 選択中の種別を hidden で保持する -->
                <input type="hidden" name="type" id="csv-type" value="inquiry">
            </div>

            <!-- ファイル選択 -->
            <div class="setting-file-row">
                <span class="setting-filename" id="filename-display">ファイルが選択されていません</span>
                <label class="setting-file-btn" for="csv-file">ファイル選択</label>
                <input type="file" id="csv-file" name="csv_file" accept=".csv" style="display:none;">
            </div>
            @error('csv_file')
                <span class="setting-error-msg">{{ $message }}</span>
            @enderror

            <!-- 実行ボタン -->
            <div class="setting-action-row">
                <button type="submit" class="setting-import-btn">CSV取込</button>
                <button type="button" id="export-btn" class="setting-export-btn">CSV出力</button>
            </div>

        </form>

        <!-- CSV出力用フォーム（GETで送信） -->
        <form id="export-form" method="GET" action="{{ route('setting.export') }}" style="display:none;">
            <input type="hidden" name="type" id="export-type" value="inquiry">
        </form>

    </div>
</div>

<script>
// データ種別の切り替え
document.querySelectorAll('.setting-type-btn').forEach(function(clickedBtn) {
    clickedBtn.addEventListener('click', function() {
        // 全ボタンから active を外す
        document.querySelectorAll('.setting-type-btn').forEach(function(eachBtn) {
            eachBtn.classList.remove('active');
        });
        // 押したボタンに active をつける
        this.classList.add('active');
        // hidden の type 値を更新する
        var selectedType = this.dataset.type;
        document.getElementById('csv-type').value    = selectedType;
        document.getElementById('export-type').value = selectedType;
    });
});

// ファイルが選ばれたらファイル名を表示する
document.getElementById('csv-file').addEventListener('change', function() {
    var selectedFile = this.files[0];
    if (selectedFile) {
        var displayName = selectedFile.name;
    } else {
        var displayName = 'ファイルが選択されていません';
    }
    document.getElementById('filename-display').textContent = displayName;
});

// CSV出力ボタン
document.getElementById('export-btn').addEventListener('click', function() {
    document.getElementById('export-form').submit();
});
</script>
@endsection
