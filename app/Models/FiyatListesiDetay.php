<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiyatListesiDetay extends Model
{
    use HasFactory;

    protected $table = 'fiyat_listesi_detaylari';

    protected $fillable = [
        'fiyat_listesi_id',
        'urun_id',
        'stok_kod',
        'stok_aciklama',
        'birim_fiyat',
        'doviz',
    ];

    protected $casts = [
        'birim_fiyat' => 'decimal:4',
    ];

    public function fiyatListesi()
    {
        return $this->belongsTo(FiyatListesi::class, 'fiyat_listesi_id');
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }
}

