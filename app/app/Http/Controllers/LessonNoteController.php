<?php

namespace App\Http\Controllers;

use App\LessonNote;
use App\Inquiry;
use Illuminate\Http\Request;

class LessonNoteController extends Controller
{
    // すべてのページでログインが必要
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 所感登録フォーム表示
    public function create(Inquiry $inquiry)
    {
        // 理解度・集中度の選択肢
        $levelLabels = LessonNote::LEVEL_LABELS;

        // この問い合わせの最新の所感を取得する（あれば）
        $latestNote = LessonNote::where('inquiry_id', $inquiry->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

        // フォームのデフォルト値を設定する
        if ($latestNote) {
            $defaultCourseName        = $latestNote->course_name;
            $defaultUnderstandingLevel = $latestNote->understanding_level;
            $defaultConcentrationLevel = $latestNote->concentration_level;
            $defaultLessonSummary     = $latestNote->lesson_summary;
            $defaultParentComment     = $latestNote->parent_comment;
            $defaultTeacherNote       = $latestNote->teacher_note;

            if ($latestNote->lesson_date) {
                $defaultLessonDate = $latestNote->lesson_date->format('Y-m-d');
            } else {
                $defaultLessonDate = '';
            }
        } else {
            $defaultCourseName         = $inquiry->desired_course_name;
            $defaultLessonDate         = '';
            $defaultUnderstandingLevel = '';
            $defaultConcentrationLevel = '';
            $defaultLessonSummary      = '';
            $defaultParentComment      = '';
            $defaultTeacherNote        = '';
        }

        return view('lesson_note.impressions', compact(
            'inquiry',
            'levelLabels',
            'defaultCourseName',
            'defaultLessonDate',
            'defaultUnderstandingLevel',
            'defaultConcentrationLevel',
            'defaultLessonSummary',
            'defaultParentComment',
            'defaultTeacherNote'
        ));
    }

    // 所感の登録処理（バリデーション → 保存 → 所感ページへ戻る）
    public function store(Request $request, Inquiry $inquiry)
    {
        // 入力チェック（授業日は必須）
        $request->validate([
            'lesson_date' => 'required|date',
        ]);

        LessonNote::create([
            'inquiry_id'          => $inquiry->id,
            'course_name'         => $request->course_name,
            'lesson_date'         => $request->lesson_date,
            'understanding_level' => $request->understanding_level,
            'concentration_level' => $request->concentration_level,
            'lesson_summary'      => $request->lesson_summary,
            'parent_comment'      => $request->parent_comment,
            'teacher_note'        => $request->teacher_note,
            'created_by'          => auth()->id(),
        ]);

        // 保存後は所感ページに戻る（フラッシュメッセージ付き）
        return redirect()->route('lesson_note.create', $inquiry)->with('success', '保存しました。');
    }
}
