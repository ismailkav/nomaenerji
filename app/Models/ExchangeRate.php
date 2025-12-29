<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'kurlar';

    protected $fillable = [
        'tarih',
        'currency_code',
        'forex_buying',
        'forex_selling',
        'banknote_buying',
        'banknote_selling',
    ];

    protected $casts = [
        'tarih' => 'date',
        'forex_buying' => 'decimal:6',
        'forex_selling' => 'decimal:6',
        'banknote_buying' => 'decimal:6',
        'banknote_selling' => 'decimal:6',
    ];
}

