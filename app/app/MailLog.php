<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = 'mail_logs';

    protected $fillable = [
        'inquiry_id',
        'sent_by',
        'to_email',
        'subject',
        'body',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
