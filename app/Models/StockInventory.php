<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockInventory extends Model
{
    use HasFactory;

    protected $table = 'stokenvanter';

    protected $fillable = [
        'depo_id',
        'stokkod',
        'stokmiktar',
    ];

    protected $casts = [
        'stokmiktar' => 'decimal:4',
    ];

    public function depo()
    {
        return $this->belongsTo(Depot::class, 'depo_id');
    }
}

