<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRecipe extends Model
{
    use HasFactory;

    protected $table = 'urunrecete';

    protected $fillable = [
        'urun_id',
        'stok_urun_id',
        'miktar',
        'sirano',
    ];

    protected $casts = [
        'miktar' => 'decimal:3',
        'sirano' => 'integer',
    ];

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }

    public function stokUrun()
    {
        return $this->belongsTo(Product::class, 'stok_urun_id');
    }
}

