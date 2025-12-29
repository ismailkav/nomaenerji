<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFicheLine extends Model
{
    use HasFactory;

    protected $table = 'stok_fis_satirlari';

    protected $fillable = [
        'stok_fis_id',
        'stokkod',
        'stokaciklama',
        'miktar',
    ];

    protected $casts = [
        'miktar' => 'decimal:4',
    ];

    public function fiche()
    {
        return $this->belongsTo(StockFiche::class, 'stok_fis_id');
    }
}

