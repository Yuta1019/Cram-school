<?php

namespace App\Http\Controllers;

use App\ContactHistory;
use App\Inquiry;
use App\User;
use Illuminate\Http\Request;

class ContactController extends Controller
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

    // 連絡履歴一覧表示（絞り込み検索）
    public function index(Request $request, Inquiry $inquiry)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // この問い合わせに紐づく連絡履歴を取得する
        $query = ContactHistory::where('inquiry_id', $inquiry->id);
        $query->with('contactedBy');

        // 日付で絞り込み
        if ($request->filled('contacted_at')) {
            $query->whereDate('contacted_at', $request->contacted_at);
        }

        // 連絡手段で絞り込み
        if ($request->filled('contact_method')) {
            $query->where('contact_method', $request->contact_method);
        }

        // 担当者で絞り込み
        if ($request->filled('contacted_by')) {
            $query->where('contacted_by', $request->contacted_by);
        }

        // 日付の新しい順に並べてすべて取得
        $query->orderBy('contacted_at', 'desc');
        $contactHistories = $query->get();

        // 担当者の選択肢
        $users = User::orderBy('name')->get();

        return view('contact.contact', compact('inquiry', 'contactHistories', 'users'));
    }

    // 連絡履歴の新規登録フォーム表示
    public function create(Inquiry $inquiry)
    {
        // 管理者のみアクセス可
        $this->checkAdmin();

        // 担当者の選択肢
        $users = User::orderBy('name')->get();

        // 対応結果の選択肢
        $responseStatusLabels = ContactHistory::RESPONSE_STATUS_LABELS;

        return view('contact.contact_newregistration', compact('inquiry', 'users', 'responseStatusLabels'));
    }

    // 連絡履歴の登録処理（バリデーション → 保存 → 一覧ページへ移動）
    public function store(Request $request, Inquiry $inquiry)
    {
        // 管理者のみアクセス可
        $this->checkAdmin();

        // 入力チェック（日付と手段は必須）
        $request->validate([
            'contacted_at'    => 'required|date',
            'contact_method'  => 'required|string|max:20',
        ]);

        // 担当者が選ばれていれば保存、空なら null にする
        if ($request->contacted_by) {
            $contactedBy = $request->contacted_by;
        } else {
            $contactedBy = null;
        }

        // datetime-local の値（例：2026-05-05T14:30）をCarbonで変換する
        $contactedAt = \Carbon\Carbon::parse($request->contacted_at);

        ContactHistory::create([
            'inquiry_id'      => $inquiry->id,
            'contacted_at'    => $contactedAt,
            'contact_method'  => $request->contact_method,
            'contacted_by'    => $contactedBy,
            'content'         => $request->content,
            'response_status' => $request->response_status,
        ]);

        return redirect()->route('contact.index', $inquiry)->with('success', '連絡履歴を登録しました。');
    }
}
