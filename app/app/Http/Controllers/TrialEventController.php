<?php

namespace App\Http\Controllers;

use App\TrialEvent;
use Illuminate\Http\Request;

class TrialEventController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 講師ロールのユーザーはアクセスを拒否する
    private function checkRole()
    {
        $role = auth()->user()->role;

        if ($role === 'instructor') {
            abort(403, 'この操作は受付・管理者のみ利用できます。');
        }
    }

    // 管理者以外のユーザーはアクセスを拒否する
    private function checkAdmin()
    {
        $role = auth()->user()->role;

        if ($role !== 'admin') {
            abort(403, 'この操作は管理者のみ利用できます。');
        }
    }

    // 体験会一覧表示（全ロールOK）
    public function index()
    {
        // 開催日の早い順に全件取得する
        $trialEvents = TrialEvent::orderBy('event_date', 'asc')->get();

        return view('trial.experience', compact('trialEvents'));
    }

    // 体験会予約カレンダー表示（全ロールOK）
    public function calendar()
    {
        // 全件取得してJavaScript用にシンプルな配列に変換する
        $eventsForJs = TrialEvent::all()->map(function ($event) {
            return [
                'id'          => $event->id,
                'event_date'  => $event->event_date->format('Y-m-d'),
                'start_time'  => $event->start_time,
                'end_time'    => $event->end_time,
                'course_name' => $event->course_name,
                'capacity'    => $event->capacity,
            ];
        });

        return view('trial.experience_calendar', compact('eventsForJs'));
    }

    // 体験会詳細表示（全ロールOK）
    public function show(TrialEvent $trialEvent)
    {
        // この体験会に紐づく予約一覧を取得する
        $reservations = $trialEvent->reservations()->with('inquiry')->get();

        return view('trial.experience_details', compact('trialEvent', 'reservations'));
    }

    // 体験会開催日の新規登録フォーム表示（受付・管理者のみ）
    public function create()
    {
        // 講師はアクセス不可
        $this->checkRole();

        return view('trial.experience_newregistration');
    }

    // 体験会開催日の登録処理（受付・管理者のみ）
    public function store(Request $request)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'course_name' => 'required|string|max:100',
            'capacity'    => 'required|integer|min:0',
        ]);

        TrialEvent::create([
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'course_name' => $request->course_name,
            'capacity'    => $request->capacity,
            'created_by'  => auth()->id(),
        ]);

        // 登録後は一覧ページに移動する
        return redirect()->route('trial.index')->with('success', '体験会を登録しました。');
    }

    // 体験会開催日の編集フォーム表示（受付・管理者のみ）
    public function edit(TrialEvent $trialEvent)
    {
        // 講師はアクセス不可
        $this->checkRole();

        return view('trial.experience_edit', compact('trialEvent'));
    }

    // 体験会開催日の編集確認ページ表示（受付・管理者のみ）
    public function confirmEdit(Request $request, TrialEvent $trialEvent)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'course_name' => 'required|string|max:100',
            'capacity'    => 'required|integer|min:0',
        ]);

        // 確認ページに渡す入力データをまとめる
        $inputData = [
            'event_date'  => $request->event_date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'course_name' => $request->course_name,
            'capacity'    => $request->capacity,
        ];

        return view('trial.experience_conf', compact('trialEvent', 'inputData'));
    }

    // 体験会開催日の更新処理（受付・管理者のみ）
    public function update(Request $request, TrialEvent $trialEvent)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック
        $request->validate([
            'event_date'  => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'course_name' => 'required|string|max:100',
            'capacity'    => 'required|integer|min:0',
        ]);

        $trialEvent->event_date  = $request->event_date;
        $trialEvent->start_time  = $request->start_time;
        $trialEvent->end_time    = $request->end_time;
        $trialEvent->course_name = $request->course_name;
        $trialEvent->capacity    = $request->capacity;
        $trialEvent->updated_at  = now();
        $trialEvent->save();

        // 更新後は一覧ページに移動する
        return redirect()->route('trial.index')->with('success', '体験会情報を更新しました。');
    }

    // 体験会開催日の削除確認ページ表示（管理者のみ）
    public function confirmDelete(TrialEvent $trialEvent)
    {
        // 管理者以外はアクセス不可
        $this->checkAdmin();

        return view('trial.experience_delete', compact('trialEvent'));
    }

    // 体験会開催日の削除処理（管理者のみ）
    public function destroy(TrialEvent $trialEvent)
    {
        // 管理者以外はアクセス不可
        $this->checkAdmin();

        $trialEvent->delete();

        // 削除後は一覧ページに移動する
        return redirect()->route('trial.index')->with('success', '体験会を削除しました。');
    }
}
