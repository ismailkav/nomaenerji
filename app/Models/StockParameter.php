<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockParameter extends Model
{
    use HasFactory;

    protected $table = 'stokparametreler';

    protected $fillable = [
        'parametre_no',
        'deger',
    ];

    protected $casts = [
        'parametre_no' => 'integer',
    ];
}

