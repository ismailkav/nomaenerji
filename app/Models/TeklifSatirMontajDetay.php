<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeklifSatirMontajDetay extends Model
{
    protected $table = 'teklif_satir_montaj_detaylari';

    protected $fillable = [
        'teklif_detay_id',
        'montaj_grup_id',
        'urun_id',
        'urun_kod',
        'birim',
        'miktar',
        'birim_fiyat',
        'doviz',
        'satir_tutar',
        'sirano',
    ];

    protected $casts = [
        'teklif_detay_id' => 'integer',
        'montaj_grup_id' => 'integer',
        'urun_id' => 'integer',
        'miktar' => 'decimal:3',
        'birim_fiyat' => 'decimal:2',
        'satir_tutar' => 'decimal:2',
        'sirano' => 'integer',
    ];
}

