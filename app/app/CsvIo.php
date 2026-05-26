<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvIo extends Model
{
    protected $table = 'csv_io';

    protected $fillable = [
        'target_type',
        'file_name_rule',
        'updated_by',
    ];

    // 更新者（usersテーブルとの紐づき）
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
