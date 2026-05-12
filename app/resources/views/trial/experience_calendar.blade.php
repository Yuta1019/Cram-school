@extends('layouts.main')

@section('content')
<div class="inq-page">

    <!-- ページタイトル -->
    <div class="inq-header">
        <h1 class="inq-title">カレンダー</h1>
    </div>

    <!-- メインカード -->
    <div class="trial-card">

        <!-- 一覧・カレンダー タブ -->
        <div class="trial-tabs">
            <a href="{{ route('trial.index') }}" class="trial-tab">一覧</a>
            <button type="button" class="trial-tab trial-tab--active">カレンダー</button>
        </div>

        @php
            use Carbon\Carbon;

            // URLパラメータから年月を取得する（なければ今月）
            $year  = (int) request('year',  now()->year);
            $month = (int) request('month', now()->month);

            $firstDay    = Carbon::create($year, $month, 1);
            $daysInMonth = $firstDay->daysInMonth;   // その月の日数
            $startDow    = $firstDay->dayOfWeek;     // 1日が何曜日か（0=日〜6=土）

            // 前月・次月のリンク用
            $prev = $firstDay->copy()->subMonth();
            $next = $firstDay->copy()->addMonth();

            // 体験会がある日付の一覧を作る（カレンダーでのハイライトに使う）
            $eventDates = $eventsForJs->pluck('event_date')->toArray();

            // カレンダーを週ごとに並べる（null = 空白セル）
            $weeks = [];
            $week  = array_fill(0, $startDow, null); // 先頭の空白を埋める
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $week[] = $d;
                if (count($week) == 7) {
                    $weeks[] = $week;
                    $week = [];
                }
            }
            // 最後の週を7セルに揃える
            while (count($week) < 7) $week[] = null;
            if (!empty($week)) $weeks[] = $week;
        @endphp

        <!-- カレンダー（左）+ 詳細（右）の2カラムレイアウト -->
        <div class="trial-cal-body">

            <!-- 左：カレンダー -->
            <div class="trial-cal-left">

                <!-- 月ナビゲーション（リンクをクリックするとページが切り替わる） -->
                <div class="trial-cal-nav">
                    <a href="?year={{ $prev->year }}&month={{ $prev->month }}" class="trial-cal-nav-btn">＜</a>
                    <span class="trial-cal-month">{{ $year }}年{{ $month }}月</span>
                    <a href="?year={{ $next->year }}&month={{ $next->month }}" class="trial-cal-nav-btn">＞</a>
                </div>

                <!-- カレンダーテーブル -->
                <table class="trial-cal-table">
                    <thead>
                        <tr>
                            <th class="trial-cal-sun">日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th class="trial-cal-sat">土</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeks as $week)
                            <tr>
                                @foreach($week as $col => $day)
                                    @if($day === null)
                                        <!-- 空白セル -->
                                        <td class="trial-cal-cell trial-cal-cell--blank"></td>
                                    @else
                                        @php
                                            $dateStr  = sprintf('%04d-%02d-%02d', $year, $month, $day);
                                            $isToday  = $dateStr === now()->format('Y-m-d');
                                            $hasEvent = in_array($dateStr, $eventDates);
                                        @endphp
                                        <!-- 日付セル：クリックで右パネルを更新する -->
                                        <td onclick="showDate('{{ $dateStr }}', this)"
                                            class="trial-cal-cell
                                                   {{ $isToday  ? 'trial-cal-cell--today'     : '' }}
                                                   {{ $hasEvent ? 'trial-cal-cell--has-event' : '' }}
                                                   {{ $col == 0 ? 'trial-cal-sun' : ($col == 6 ? 'trial-cal-sat' : '') }}">
                                            <span>{{ $day }}</span>
                                            @if($hasEvent)
                                                <span class="trial-cal-dot"></span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- 右：クリックされた日付の詳細 -->
            <div class="trial-cal-right">
                <div id="cal-detail" class="trial-cal-detail">
                    <p class="trial-cal-detail-empty">日付をクリックしてください</p>
                </div>
                <div class="trial-cal-detail-footer">
                    <a id="detail-link" href="#" class="inqdetail-btn-submit" style="display:none;">詳細を開く</a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // PHPから受け取った体験会データ
    var trialEvents   = @json($eventsForJs);
    var detailBaseUrl = '{{ url('/trial-events') }}';

    // 日付セルをクリックしたときに右パネルを更新する関数
    function showDate(dateStr, el) {

        // 前の選択を解除して、クリックしたセルを選択状態にする
        document.querySelectorAll('.trial-cal-cell--selected')
            .forEach(c => c.classList.remove('trial-cal-cell--selected'));
        el.classList.add('trial-cal-cell--selected');

        // その日の体験会を絞り込む
        var events = trialEvents.filter(e => e.event_date === dateStr);

        // 日付を日本語に変換（例: "2026-05-01" → "2026年5月1日"）
        var [y, m, d] = dateStr.split('-');
        var dlabel = `${y}年${parseInt(m)}月${parseInt(d)}日`;

        var detail     = document.getElementById('cal-detail');
        var detailLink = document.getElementById('detail-link');

        if (events.length === 0) {
            // 体験会がない日
            detail.innerHTML = `
                <p class="trial-cal-detail-date">${dlabel}</p>
                <p class="trial-cal-detail-empty">体験会はありません</p>
            `;
            detailLink.style.display = 'none';
        } else {
            // 体験会がある日：各イベントのHTMLを作って結合する
            var rows = events.map(e => `
                <div class="trial-cal-event-row">
                    <div class="trial-cal-event-field">
                        <span class="trial-cal-event-label">時間</span>
                        <span>${e.start_time} 〜 ${e.end_time}</span>
                    </div>
                    <div class="trial-cal-event-field">
                        <span class="trial-cal-event-label">コース</span>
                        <span>${e.course_name}</span>
                    </div>
                    <div class="trial-cal-event-field">
                        <span class="trial-cal-event-label">定員</span>
                        <span>${e.capacity} 名</span>
                    </div>
                </div>
            `).join('');

            detail.innerHTML = `<p class="trial-cal-detail-date">${dlabel}</p>${rows}`;
            detailLink.href  = `${detailBaseUrl}/${events[0].id}`;
            detailLink.style.display = 'inline-flex';
        }
    }
</script>
@endsection
