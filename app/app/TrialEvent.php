<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrialEvent extends Model
{
    protected $fillable = [
        'event_date',
        'start_time',
        'end_time',
        'course_name',
        'capacity',
        'reserved_count',
        'status',
        'created_by',
    ];

    // 状態の表示ラベル
    const STATUS_LABELS = [
        0 => '空きあり',
        1 => '満員',
    ];

    // event_date を日付として扱う（format() が使えるようになる）
    protected $dates = ['event_date'];

    // この体験会に紐づく予約一覧
    public function reservations()
    {
        return $this->hasMany(TrialReservation::class);
    }

    // 登録した担当者
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
