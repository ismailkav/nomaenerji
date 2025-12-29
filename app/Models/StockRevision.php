<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRevision extends Model
{
    use HasFactory;

    protected $table = 'stokrevize';

    protected $fillable = [
        'stokkod',
        'miktar',
        'siparissatirid',
        'depo_id',
        'durum',
    ];

    protected $casts = [
        'miktar' => 'decimal:3',
        'siparissatirid' => 'int',
        'depo_id' => 'int',
    ];
}
