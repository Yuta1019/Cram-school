<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonNote extends Model
{
    protected $fillable = [
        'inquiry_id',
        'course_name',
        'lesson_date',
        'understanding_level',
        'concentration_level',
        'lesson_summary',
        'parent_comment',
        'teacher_note',
        'created_by',
    ];

    // 理解度・集中度の表示ラベル
    const LEVEL_LABELS = [
        0 => '良い',
        1 => '普通',
        2 => '要支援',
    ];

    // lesson_date を日付として扱う（format() が使えるようになる）
    protected $dates = ['lesson_date'];

    // この所感が属する問い合わせ
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    // 登録した担当者
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
