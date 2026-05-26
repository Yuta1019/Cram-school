<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\TrialEvent;
use App\TrialReservation;
use Illuminate\Http\Request;

class TrialReservationController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 講師ロールのユーザーはアクセスを拒否する
    private function checkRole()
    {
        if (auth()->user()->role === 'teacher') {
            abort(403, 'この操作は受付・管理者のみ利用できます。');
        }
    }

    // 管理者以外のユーザーはアクセスを拒否する
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'この操作は管理者のみ利用できます。');
        }
    }

    // 体験会予約ページ表示（受付・管理者のみ）
    public function create(Inquiry $inquiry)
    {
        $this->checkRole();

        // 開催日の早い順に全件取得する
        $trialEvents = TrialEvent::orderBy('event_date', 'asc')->get();

        return view('trial.experience_reservation', compact('inquiry', 'trialEvents'));
    }

    // 体験会予約確認ページ表示（受付・管理者のみ）
    public function confirm(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

        // 選択された体験会IDが存在するかチェック
        $request->validate([
            'trial_event_id' => 'required|exists:trial_events,id',
        ]);

        // 選択した体験会を取得する
        $trialEvent = TrialEvent::findOrFail($request->trial_event_id);

        // 定員に達していたら予約ページに戻す
        if ($trialEvent->reserved_count >= $trialEvent->capacity) {
            return redirect()->back()
                ->with('error', 'この体験会は定員に達しているため予約できません。');
        }

        return view('trial.experience_reservation_conf', compact('inquiry', 'trialEvent'));
    }

    // 体験会予約登録処理（受付・管理者のみ）
    public function store(Request $request, Inquiry $inquiry)
    {
        $this->checkRole();

        // 選択された体験会IDが存在するかチェック
        $request->validate([
            'trial_event_id' => 'required|exists:trial_events,id',
        ]);

        // 確認ページ表示後に他の人が予約した場合も考慮して再チェック
        $trialEvent = TrialEvent::findOrFail($request->trial_event_id);
        if ($trialEvent->reserved_count >= $trialEvent->capacity) {
            return redirect()->route('trial.reservation.create', $inquiry)
                ->with('error', 'この体験会は定員に達しているため予約できません。');
        }

        TrialReservation::create([
            'inquiry_id'         => $inquiry->id,
            'trial_event_id'     => $request->trial_event_id,
            'reservation_status' => '予約済み',
            'reserved_at'        => now(),
            'created_by'         => auth()->id(),
        ]);

        // 予約数を +1 する
        $trialEvent->increment('reserved_count');

        // 定員に達したらステータスを満員（1）にする
        if ($trialEvent->reserved_count >= $trialEvent->capacity) {
            $trialEvent->status = 1;
            $trialEvent->save();
        }

        // 予約確定後は体験会一覧ページへ遷移する
        return redirect()->route('trial.index')
            ->with('success', '体験会を予約しました。');
    }

    // 予約取り消し処理（管理者のみ）
    public function destroy(TrialReservation $reservation)
    {
        // 管理者以外はアクセス不可
        $this->checkAdmin();

        // どの体験会詳細ページに戻るかをメモしておく
        $trialEventId = $reservation->trial_event_id;

        $reservation->delete();

        // 予約数を -1 して、満員だった場合は空きありに戻す
        $trialEvent = TrialEvent::find($trialEventId);
        if ($trialEvent) {
            $trialEvent->decrement('reserved_count');
            if ($trialEvent->status == 1) {
                $trialEvent->status = 0;
                $trialEvent->save();
            }
        }

        // 取り消し後は同じ体験会の詳細ページに戻る
        return redirect()->route('trial.show', $trialEventId)
            ->with('success', '予約を取り消しました。');
    }
}
