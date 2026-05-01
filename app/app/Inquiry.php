<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'parent_name',
        'parent_phone',
        'parent_email',
        'preferred_contact_method',
        'student_name',
        'school_name',
        'grade',
        'desired_course_name',
        'status',
        'assigned_user_id',
        'inquiry_content',
        'memo',
        'last_contact_at',
    ];

    const STATUS_LABELS = [
        0 => '未対応',
        1 => '検討中',
        2 => '入会',
        3 => '辞退',
    ];

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? '不明';
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
