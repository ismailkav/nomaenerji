<?php

namespace App\Support;

final class OrderLineColumns
{
    /**
     * @return array<int, array{key:string,label:string}>
     */
    public static function definitions(): array
    {
        return [
            ['key' => 'stok_kod', 'label' => 'Stok Kod'],
            ['key' => 'stok_aciklama', 'label' => 'Stok Açıklama'],
            ['key' => 'proje_kodu', 'label' => 'Proje Kodu'],
            ['key' => 'durum', 'label' => 'Durum'],
            ['key' => 'birim_fiyat', 'label' => 'Birim Fiyat'],
            ['key' => 'miktar', 'label' => 'Miktar'],
            ['key' => 'gelen', 'label' => 'Gelen'],
            ['key' => 'doviz', 'label' => 'Döviz'],
            ['key' => 'kur', 'label' => 'Kur'],
            ['key' => 'isk1', 'label' => 'İsk.1%'],
            ['key' => 'isk2', 'label' => 'İsk.2%'],
            ['key' => 'isk3', 'label' => 'İsk.3%'],
            ['key' => 'isk4', 'label' => 'İsk.4%'],
            ['key' => 'isk5', 'label' => 'İsk.5%'],
            ['key' => 'isk6', 'label' => 'İsk.6%'],
            ['key' => 'isk_tutar', 'label' => 'İsk. Tutar'],
            ['key' => 'kdv_orani', 'label' => 'KDV %'],
            ['key' => 'kdv_durum', 'label' => 'KDV Durum'],
            ['key' => 'satir_tutar', 'label' => 'Satır Tutar'],
            ['key' => 'detay', 'label' => 'Detay'],
        ];
    }
}
