<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 講師ロールのユーザーはアクセスを拒否する
    // create・store・edit・update・confirmEdit の各メソッドの先頭で呼び出す
    private function checkRole()
    {
        $role = auth()->user()->role;

        if ($role === 'instructor') {
            abort(403, 'この操作は受付・管理者のみ利用できます。');
        }
    }

    // 管理者以外のユーザーはアクセスを拒否する
    // confirmDelete・destroy の各メソッドの先頭で呼び出す
    private function checkAdmin()
    {
        $role = auth()->user()->role;

        if ($role !== 'admin') {
            abort(403, 'この操作は管理者のみ利用できます。');
        }
    }

    // 問い合わせ一覧表示（絞り込み検索）
    public function index(Request $request)
    {
        $query = Inquiry::with('assignedUser');

        // 生徒名で部分一致検索
        if ($request->filled('student_name')) {
            $searchWord = '%' . $request->student_name . '%';
            $query->where('student_name', 'like', $searchWord);
        }

        // 保護者名で部分一致検索
        if ($request->filled('parent_name')) {
            $searchWord = '%' . $request->parent_name . '%';
            $query->where('parent_name', 'like', $searchWord);
        }

        // 希望コース名で部分一致検索
        if ($request->filled('course')) {
            $searchWord = '%' . $request->course . '%';
            $query->where('desired_course_name', 'like', $searchWord);
        }

        // 状態で完全一致検索
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $query->orderBy('created_at', 'desc');
        $inquiries    = $query->get();
        $statusLabels = Inquiry::STATUS_LABELS;

        return view('inquiry.index', compact('inquiries', 'statusLabels'));
    }

    // 新規登録フォーム表示
    public function create()
    {
        // 講師はアクセス不可
        $this->checkRole();

        $statusLabels = Inquiry::STATUS_LABELS;
        $users        = User::orderBy('name')->get();

        return view('inquiry.Inquiry_newregistration', compact('statusLabels', 'users'));
    }

    // 新規登録処理（バリデーション → 保存 → 詳細ページへ移動）
    public function store(Request $request)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック（保護者名・生徒名は必須）
        $request->validate([
            'parent_name'  => 'required|string|max:100',
            'student_name' => 'required|string|max:100',
        ]);

        // ステータスが選ばれていなければ 0（未対応）にする
        if ($request->status !== null) {
            $status = $request->status;
        } else {
            $status = 0;
        }

        // 最終連絡日が入力されていれば保存、空なら null にする
        if ($request->last_contact_at) {
            $lastContactAt = $request->last_contact_at;
        } else {
            $lastContactAt = null;
        }

        $inquiry = Inquiry::create([
            'parent_name'              => $request->parent_name,
            'parent_phone'             => $request->parent_phone,
            'parent_email'             => $request->parent_email,
            'preferred_contact_method' => $request->preferred_contact_method,
            'student_name'             => $request->student_name,
            'school_name'              => $request->school_name,
            'grade'                    => $request->grade,
            'desired_course_name'      => $request->desired_course_name,
            'inquiry_content'          => $request->inquiry_content,
            'status'                   => $status,
            'assigned_user_id'         => Auth::id(), // ログイン中のユーザーのIDを自動でセット
            'memo'                     => $request->memo,
            'last_contact_at'          => $lastContactAt,
        ]);

        return redirect()->route('inquiry.show', $inquiry)->with('success', '問い合わせを登録しました。');
    }

    // 問い合わせ詳細表示
    public function show(Inquiry $inquiry)
    {
        return view('inquiry.Inquiry_details', compact('inquiry'));
    }

    // 編集フォーム表示
    public function edit(Inquiry $inquiry)
    {
        // 講師はアクセス不可
        $this->checkRole();

        $statusLabels = Inquiry::STATUS_LABELS;
        $users        = User::orderBy('name')->get();

        return view('inquiry.Inquiry_edit', compact('inquiry', 'statusLabels', 'users'));
    }

    // 更新処理（バリデーション → 保存 → 詳細ページへ移動）
    public function update(Request $request, Inquiry $inquiry)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック（保護者名・生徒名は必須）
        $request->validate([
            'parent_name'  => 'required|string|max:100',
            'student_name' => 'required|string|max:100',
        ]);

        // ステータスが選ばれていなければ 0（未対応）にする
        if ($request->status !== null) {
            $status = $request->status;
        } else {
            $status = 0;
        }

        // 最終連絡日が入力されていれば保存、空なら null にする
        if ($request->last_contact_at) {
            $lastContactAt = $request->last_contact_at;
        } else {
            $lastContactAt = null;
        }

        // 受付担当が選ばれていれば保存、空なら null にする
        if ($request->assigned_user_id) {
            $assignedUserId = $request->assigned_user_id;
        } else {
            $assignedUserId = null;
        }

        $inquiry->update([
            'parent_name'         => $request->parent_name,
            'parent_phone'        => $request->parent_phone,
            'parent_email'        => $request->parent_email,
            'student_name'        => $request->student_name,
            'school_name'         => $request->school_name,
            'grade'               => $request->grade,
            'desired_course_name' => $request->desired_course_name,
            'inquiry_content'     => $request->inquiry_content,
            'status'              => $status,
            'assigned_user_id'    => $assignedUserId,
            'memo'                => $request->memo,
            'last_contact_at'     => $lastContactAt,
            'updated_at'          => now(),
        ]);

        return redirect()->route('inquiry.show', $inquiry)->with('success', '問い合わせを更新しました。');
    }

    // 編集内容の確認ページ表示
    // 編集フォームから送信されたデータをバリデーションして確認ページに渡す
    public function confirmEdit(Request $request, Inquiry $inquiry)
    {
        // 講師はアクセス不可
        $this->checkRole();

        // 入力チェック（保護者名・生徒名は必須）
        $request->validate([
            'parent_name'  => 'required|string|max:100',
            'student_name' => 'required|string|max:100',
        ]);

        // フォームの入力値を1つずつ取り出してまとめる
        $inputData = [
            'parent_name'         => $request->parent_name,
            'parent_phone'        => $request->parent_phone,
            'parent_email'        => $request->parent_email,
            'student_name'        => $request->student_name,
            'school_name'         => $request->school_name,
            'grade'               => $request->grade,
            'desired_course_name' => $request->desired_course_name,
            'inquiry_content'     => $request->inquiry_content,
            'status'              => $request->status,
            'assigned_user_id'    => $request->assigned_user_id,
            'memo'                => $request->memo,
        ];

        // 状態の数値から表示ラベルを取得する（例：0 → 「未対応」）
        $status          = $inputData['status'];
        $allStatusLabels = Inquiry::STATUS_LABELS;
        if (isset($allStatusLabels[$status])) {
            $statusLabel = $allStatusLabels[$status];
        } else {
            $statusLabel = '未対応';
        }

        // 担当者IDから担当者名を取得する
        $assignedUserName = '未選択';
        if ($inputData['assigned_user_id']) {
            $assignedUser = User::find($inputData['assigned_user_id']);
            if ($assignedUser) {
                $assignedUserName = $assignedUser->name;
            }
        }

        return view('inquiry.Inquiry_conf', compact('inquiry', 'inputData', 'statusLabel', 'assignedUserName'));
    }

    // 削除確認ページ表示
    public function confirmDelete(Inquiry $inquiry)
    {
        // 管理者以外はアクセス不可
        $this->checkAdmin();

        return view('inquiry.Inquiry_delete', compact('inquiry'));
    }

    // 削除処理
    public function destroy(Inquiry $inquiry)
    {
        // 管理者以外はアクセス不可
        $this->checkAdmin();

        $inquiry->delete();

        return redirect()->route('inquiry.index')->with('success', '問い合わせを削除しました。');
    }
}
