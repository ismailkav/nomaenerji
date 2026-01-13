<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $table = 'parametreler';

    protected $fillable = [
        'anahtar',
        'deger',
    ];
}

