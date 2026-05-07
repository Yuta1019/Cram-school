<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrialReservation extends Model
{
    protected $fillable = [
        'inquiry_id',
        'trial_event_id',
        'reservation_status',
        'reserved_at',
        'checked_in_at',
        'attendance_status',
        'note',
        'created_by',
    ];

    // 日付として扱うカラム（format() が使えるようになる）
    protected $dates = ['reserved_at', 'checked_in_at'];

    // この予約が属する問い合わせ
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    // この予約が属する体験会
    public function trialEvent()
    {
        return $this->belongsTo(TrialEvent::class);
    }

    // 作成した担当者
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
