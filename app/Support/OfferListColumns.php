<?php

namespace App\Support;

final class OfferListColumns
{
    /**
     * @return array<int, array{key:string,label:string,default?:bool}>
     */
    public static function definitions(): array
    {
        return [
            ['key' => 'teklif_no', 'label' => 'Teklif No', 'default' => true],
            ['key' => 'revize_no', 'label' => 'Revize No', 'default' => true],
            ['key' => 'tarih', 'label' => 'Teklif Tarih', 'default' => true],
            ['key' => 'gecerlilik_tarihi', 'label' => 'Geçerlilik Tarihi', 'default' => false],
            ['key' => 'gecen_sure', 'label' => 'Geçen Süre', 'default' => true],
            ['key' => 'teklif_durum', 'label' => 'Teklif Durum', 'default' => true],
            ['key' => 'gerceklesme_olasiligi', 'label' => 'Gerçekleşme Olasılığı', 'default' => true],
            ['key' => 'islem_turu', 'label' => 'İşlem Türü', 'default' => true],
            ['key' => 'proje', 'label' => 'Proje', 'default' => true],
            ['key' => 'carikod', 'label' => 'Cari Kod', 'default' => true],
            ['key' => 'cariaciklama', 'label' => 'Cari Açıklama', 'default' => true],
            ['key' => 'onay_durum', 'label' => 'Onay Durum', 'default' => true],
            ['key' => 'onay_tarihi', 'label' => 'Onay Tarih', 'default' => true],
            ['key' => 'hazirlayan', 'label' => 'Hazırlayan', 'default' => true],
            ['key' => 'teklif_doviz', 'label' => 'Teklif Döviz', 'default' => false],
            ['key' => 'teklif_kur', 'label' => 'Teklif Kur', 'default' => false],
            ['key' => 'alt_toplam', 'label' => 'Alt Toplam', 'default' => false],
            ['key' => 'iskonto_tutar', 'label' => 'İskonto Tutar', 'default' => false],
            ['key' => 'kdv', 'label' => 'KDV', 'default' => false],
            ['key' => 'genel_toplam', 'label' => 'Genel Toplam (TL)', 'default' => false],
            ['key' => 'toplam_tutar', 'label' => 'Toplam Tutar', 'default' => true],
            ['key' => 'aciklama', 'label' => 'Açıklama', 'default' => false],
            ['key' => 'islem', 'label' => 'İşlem', 'default' => true],
        ];
    }
}

