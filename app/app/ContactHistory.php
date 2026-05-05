<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactHistory extends Model
{
    protected $fillable = [
        'inquiry_id',
        'contact_method',
        'contacted_by',
        'contacted_at',
        'content',
        'response_status',
    ];

    // 対応結果の表示ラベル
    const RESPONSE_STATUS_LABELS = [
        0 => '返信待ち',
        1 => '対応完了',
    ];

    // contacted_at を日付として扱う（format() が使えるようになる）
    protected $dates = ['contacted_at'];

    // この連絡履歴が属する問い合わせ
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    // 対応した担当者
    public function contactedBy()
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }
}
