<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siparis extends Model
{
    use HasFactory;

    protected $table = 'siparisler';

    const CREATED_AT = 'ekleme_tarihi';
    const UPDATED_AT = 'son_guncelleme_tarihi';

    protected $fillable = [
        'siparis_turu',
        'carikod',
        'cariaciklama',
        'tarih',
        'gecerlilik_tarihi',
        'siparis_no',
        'teklif_no',
        'aciklama',
        'siparis_durum',
        'planlama_durum',
        'planlanan_miktar',
        'onay_durum',
        'onay_tarihi',
        'yetkili_personel',
        'hazirlayan',
        'islem_turu_id',
        'proje_id',
        'siparis_doviz',
        'siparis_kur',
        'ekleme_tarihi',
        'son_guncelleme_tarihi',
        'toplam',
        'iskonto_tutar',
        'kdv',
        'genel_toplam',
    ];

    protected $casts = [
        'tarih'                 => 'date',
        'gecerlilik_tarihi'     => 'date',
        'onay_tarihi'           => 'date',
        'ekleme_tarihi'         => 'datetime',
        'son_guncelleme_tarihi' => 'datetime',
        'siparis_kur'           => 'decimal:4',
        'toplam'                => 'decimal:2',
        'iskonto_tutar'         => 'decimal:2',
        'kdv'                   => 'decimal:2',
        'genel_toplam'          => 'decimal:2',
        'planlanan_miktar'      => 'decimal:2',
    ];

    public function detaylar()
    {
        return $this->hasMany(SiparisDetay::class, 'siparis_id');
    }

    public function islemTuru()
    {
        return $this->belongsTo(IslemTuru::class, 'islem_turu_id');
    }

    public function proje()
    {
        return $this->belongsTo(Project::class, 'proje_id');
    }
}
