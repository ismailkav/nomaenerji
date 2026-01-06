<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTable extends Model
{
    protected $table = 'usertable';

    protected $fillable = [
        'kullanicikod',
        'sayfa',
        'sutun',
        'durum',
        'sirano',
    ];

    protected $casts = [
        'durum' => 'boolean',
        'sirano' => 'integer',
    ];
}
