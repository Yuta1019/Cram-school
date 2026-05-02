<?php

namespace App\Http\Controllers;

use App\Inquiry;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    // 全アクションにログイン必須を適用
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 問い合わせ一覧表示（絞り込み検索）
    public function index(Request $request)
    {
        $query = Inquiry::with('assignedUser');

        // 生徒名で部分一致検索
        if ($request->filled('student_name')) {
            $query->where('student_name', 'like', '%' . $request->student_name . '%');
        }

        // 保護者名で部分一致検索
        if ($request->filled('parent_name')) {
            $query->where('parent_name', 'like', '%' . $request->parent_name . '%');
        }

        // 希望コース名で部分一致検索
        if ($request->filled('course')) {
            $query->where('desired_course_name', 'like', '%' . $request->course . '%');
        }

        // 状態で完全一致検索
        if ($request->filled('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $inquiries    = $query->orderBy('created_at', 'desc')->get();
        $statusLabels = Inquiry::STATUS_LABELS;

        return view('inquiry.index', compact('inquiries', 'statusLabels'));
    }

    // 新規登録フォーム表示
    public function create()
    {
        $statusLabels = Inquiry::STATUS_LABELS;
        $users        = User::orderBy('name')->get();

        return view('inquiry.Inquiry_newregistration', compact('statusLabels', 'users'));
    }

    // 新規登録処理（バリデーション→保存→詳細ページへリダイレクト）
    public function store(Request $request)
    {
        $request->validate([
            'parent_name'  => 'required|string|max:100',
            'student_name' => 'required|string|max:100',
        ]);

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
            'status'                   => $request->status ?? 0,
            'assigned_user_id'         => Auth::id(),
            'memo'                     => $request->memo,
            'last_contact_at'          => $request->last_contact_at ?: null,
        ]);

        return redirect()->route('inquiry.show', $inquiry)->with('success', '問い合わせを登録しました。');
    }

    // 問い合わせ詳細表示
    public function show(Inquiry $inquiry)
    {
        return view('inquiry.Inquiry_details', compact('inquiry'));
    }
}
