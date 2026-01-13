<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeklifSatirTakimDetay extends Model
{
    use HasFactory;

    protected $table = 'teklif_satir_takim_detaylari';

    protected $fillable = [
        'teklif_detay_id',
        'urun_id',
        'stokkod',
        'stok_aciklama',
        'miktar',
        'birim_fiyat',
        'iskonto1',
        'iskonto2',
        'doviz',
        'kur',
        'satir_tutar',
    ];

    public function teklifDetay()
    {
        return $this->belongsTo(TeklifDetay::class, 'teklif_detay_id');
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }
}
