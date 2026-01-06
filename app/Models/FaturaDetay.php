<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaturaDetay extends Model
{
    use HasFactory;

    protected $table = 'fatura_detaylari';

    protected $fillable = [
        'fatura_id',
        'urun_id',
        'proje_kodu',
        'stokkod',
        'siparis_detay_id',
        'satir_aciklama',
        'durum',
        'miktar',
        'birim',
        'birim_fiyat',
        'doviz',
        'kur',
        'iskonto1',
        'iskonto2',
        'iskonto3',
        'iskonto4',
        'iskonto5',
        'iskonto6',
        'iskonto_tutar',
        'kdv_orani',
        'kdv_tutar',
        'satir_toplam',
    ];

    public function fatura()
    {
        return $this->belongsTo(Fatura::class, 'fatura_id');
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }

    public function siparisDetay()
    {
        return $this->belongsTo(SiparisDetay::class, 'siparis_detay_id');
    }
}
