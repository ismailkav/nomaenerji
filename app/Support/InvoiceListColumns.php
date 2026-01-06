<?php

namespace App\Support;

final class InvoiceListColumns
{
    /**
     * @return array<int, array{key:string,label:string,default?:bool}>
     */
    public static function definitions(): array
    {
        return [
            ['key' => 'siparis_no', 'label' => 'Fatura No', 'default' => true],
            ['key' => 'belge_no', 'label' => 'Belge No', 'default' => true],
            ['key' => 'tarih', 'label' => 'Fatura Tarih', 'default' => true],
            ['key' => 'carikod', 'label' => 'Cari Kod', 'default' => true],
            ['key' => 'cariaciklama', 'label' => 'Cari Açıklama', 'default' => true],
            ['key' => 'onay_durum', 'label' => 'Onay Durum', 'default' => true],
            ['key' => 'onay_tarihi', 'label' => 'Onay Tarih', 'default' => true],
            ['key' => 'hazirlayan', 'label' => 'Hazırlayan', 'default' => true],
            ['key' => 'toplam_tutar', 'label' => 'Toplam Tutar', 'default' => true],
            ['key' => 'islem', 'label' => 'İşlem', 'default' => true],
        ];
    }
}

