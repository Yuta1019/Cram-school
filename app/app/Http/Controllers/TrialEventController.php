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

    // 体験会一覧表示（全ロールOK）
    public function index()
    {
        // 開催日の早い順に全件取得する
        $trialEvents = TrialEvent::orderBy('event_date', 'asc')->get();

        return view('trial.experience', compact('trialEvents'));
    }

    // 体験会詳細表示（全ロールOK）
    public function show(TrialEvent $trialEvent)
    {
        // この体験会に紐づく予約一覧を取得する（問い合わせ情報も一緒に取得）
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
}
