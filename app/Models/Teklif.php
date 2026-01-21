<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teklif extends Model
{
    use HasFactory;

    protected $table = 'teklifler';

    const CREATED_AT = 'ekleme_tarihi';
    const UPDATED_AT = 'son_guncelleme_tarihi';

    protected $fillable = [
        'carikod',
        'cariaciklama',
        'tarih',
        'gecerlilik_tarihi',
        'teklif_no',
        'revize_no',
        'revize_tarihi',
        'aciklama',
        'teklif_durum',
        'gerceklesme_olasiligi',
        'onay_durum',
        'onay_tarihi',
        'yetkili_personel',
        'hazirlayan',
        'islem_turu_id',
        'proje_id',
        'proje_turu_id',
        'teklif_doviz',
        'teklif_kur',
        'ekleme_tarihi',
        'son_guncelleme_tarihi',
        'toplam',
        'iskonto_tutar',
        'kdv',
        'genel_toplam',
    ];

    protected $casts = [
        'tarih'                  => 'date',
        'gecerlilik_tarihi'      => 'date',
        'revize_tarihi'          => 'date',
        'onay_tarihi'            => 'date',
        'ekleme_tarihi'          => 'datetime',
        'son_guncelleme_tarihi'  => 'datetime',
        'teklif_kur'             => 'decimal:4',
        'toplam'                 => 'decimal:2',
        'iskonto_tutar'          => 'decimal:2',
        'kdv'                    => 'decimal:2',
        'genel_toplam'           => 'decimal:2',
    ];

    public function detaylar()
    {
        return $this->hasMany(TeklifDetay::class, 'teklif_id');
    }

    public function islemTuru()
    {
        return $this->belongsTo(IslemTuru::class, 'islem_turu_id');
    }

    public function proje()
    {
        return $this->belongsTo(Project::class, 'proje_id');
    }

    public function projeTuru()
    {
        return $this->belongsTo(ProjectType::class, 'proje_turu_id');
    }
}
