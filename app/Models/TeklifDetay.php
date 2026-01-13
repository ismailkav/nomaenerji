<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class TeklifDetay extends Model
{
    use HasFactory;

    protected $table = 'teklif_detaylari';

    protected $fillable = [
        'teklif_id',
        'urun_id',
        'satir_aciklama',
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

    public function teklif()
    {
        return $this->belongsTo(Teklif::class);
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }

    public function takimDetaylari()
    {
        return $this->hasMany(TeklifSatirTakimDetay::class, 'teklif_detay_id');
    }
}
