<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IslemTuru extends Model
{
    use HasFactory;

    protected $table = 'islem_turleri';

    protected $fillable = [
        'ad',
    ];
}

