<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiparisDetay extends Model
{
    use HasFactory;

    protected $table = 'siparis_detaylari';

    protected $fillable = [
        'siparis_id',
        'urun_id',
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

    public function siparis()
    {
        return $this->belongsTo(Siparis::class, 'siparis_id');
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }

    public function alimEslestirmeleri()
    {
        return $this->hasMany(SiparisSatirEslestirme::class, 'alim_detay_id');
    }

    public function satisEslestirmeleri()
    {
        return $this->hasMany(SiparisSatirEslestirme::class, 'satis_detay_id');
    }
}
