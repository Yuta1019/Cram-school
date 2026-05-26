<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\TrialEvent;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 管理者以外のユーザーはアクセスを拒否する
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'この操作は管理者のみ利用できます。');
        }
    }

    // 設定ページ表示（管理者のみ）
    public function index()
    {
        $this->checkAdmin();

        return view('setting.setting');
    }

    // CSV取込（管理者のみ）
    public function import(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'type'     => 'required|in:inquiry,trial',
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $uploadedFile = $request->file('csv_file');
        $csvFile      = fopen($uploadedFile->getPathname(), 'r');

        // ファイルが開けなかった場合はエラーを返す
        if ($csvFile === false) {
            return redirect()->route('setting.index')
                ->with('error', 'ファイルを開けませんでした。');
        }

        // 1行目（ヘッダー行）をスキップする
        fgetcsv($csvFile);

        $registeredCount = 0;

        try {

            if ($request->type === 'inquiry') {

                // ラベル（例:「検討中」）→ 数値（例:1）に変換するための配列を作る
                $statusLabelToNumber = array_flip(Inquiry::STATUS_LABELS);

                // お問い合わせCSVを1行ずつ読み込んで登録する
                while (($csvRow = fgetcsv($csvFile)) !== false) {
                    // 列数が足りない行はスキップする
                    if (count($csvRow) < 10) {
                        continue;
                    }

                    // ステータスのラベルを数値に変換する（変換できない場合は 0 にする）
                    if (isset($statusLabelToNumber[$csvRow[8]])) {
                        $statusNumber = $statusLabelToNumber[$csvRow[8]];
                    } else {
                        $statusNumber = 0;
                    }

                    Inquiry::create([
                        'parent_name'              => $csvRow[0],
                        'parent_phone'             => $csvRow[1],
                        'parent_email'             => $csvRow[2],
                        'preferred_contact_method' => $csvRow[3],
                        'student_name'             => $csvRow[4],
                        'school_name'              => $csvRow[5],
                        'grade'                    => $csvRow[6],
                        'desired_course_name'      => $csvRow[7],
                        'status'                   => $statusNumber,
                        'inquiry_content'          => $csvRow[9],
                    ]);
                    $registeredCount++;
                }

            } else {

                // 体験会情報CSVを1行ずつ読み込んで登録する
                while (($csvRow = fgetcsv($csvFile)) !== false) {
                    // 列数が足りない行はスキップする
                    if (count($csvRow) < 5) {
                        continue;
                    }

                    TrialEvent::create([
                        'event_date'  => $csvRow[0],
                        'start_time'  => $csvRow[1],
                        'end_time'    => $csvRow[2],
                        'course_name' => $csvRow[3],
                        'capacity'    => $csvRow[4],
                        'status'      => 0,
                        'created_by'  => auth()->id(),
                    ]);
                    $registeredCount++;
                }

            }

        } catch (\Exception $e) {
            fclose($csvFile);
            return redirect()->route('setting.index')
                ->with('error', 'CSVの取り込みに失敗しました。ファイルの内容を確認してください。');
        }

        fclose($csvFile);

        return redirect()->route('setting.index')
            ->with('success', $registeredCount . '件のデータを取り込みました。');
    }

    // CSV出力（管理者のみ）
    public function export(Request $request)
    {
        $this->checkAdmin();

        $dataType = $request->input('type', 'inquiry');

        if ($dataType === 'inquiry') {

            $downloadFileName = 'inquiry_' . date('Ymd') . '.csv';
            $allRecords       = Inquiry::all();
            $columnHeaders    = ['保護者名', '電話番号', 'メール', '連絡希望方法', '生徒名', '学校名', '学年', '希望コース', 'ステータス', '問い合わせ内容'];

            $writeCsvData = function () use ($allRecords, $columnHeaders) {
                $outputStream = fopen('php://output', 'w');
                // ExcelでのCSV文字化けを防ぐBOMを付ける
                fprintf($outputStream, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($outputStream, $columnHeaders);
                foreach ($allRecords as $record) {
                    fputcsv($outputStream, [
                        $record->parent_name,
                        $record->parent_phone,
                        $record->parent_email,
                        $record->preferred_contact_method,
                        $record->student_name,
                        $record->school_name,
                        $record->grade,
                        $record->desired_course_name,
                        $record->status_label,
                        $record->inquiry_content,
                    ]);
                }
                fclose($outputStream);
            };

        } else {

            $downloadFileName = 'trial_' . date('Ymd') . '.csv';
            $allRecords       = TrialEvent::all();
            $columnHeaders    = ['開催日', '開始時間', '終了時間', 'コース名', '定員', '予約数', 'ステータス'];

            $writeCsvData = function () use ($allRecords, $columnHeaders) {
                $outputStream = fopen('php://output', 'w');
                fprintf($outputStream, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($outputStream, $columnHeaders);
                foreach ($allRecords as $record) {
                    if ($record->event_date) {
                        $formattedDate = $record->event_date->format('Y-m-d');
                    } else {
                        $formattedDate = '';
                    }

                    if (isset(TrialEvent::STATUS_LABELS[$record->status])) {
                        $statusLabel = TrialEvent::STATUS_LABELS[$record->status];
                    } else {
                        $statusLabel = '';
                    }

                    fputcsv($outputStream, [
                        $formattedDate,
                        $record->start_time,
                        $record->end_time,
                        $record->course_name,
                        $record->capacity,
                        $record->reserved_count,
                        $statusLabel,
                    ]);
                }
                fclose($outputStream);
            };

        }

        return response()->streamDownload($writeCsvData, $downloadFileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
