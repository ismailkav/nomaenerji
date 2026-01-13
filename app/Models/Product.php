<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'urunler';

    protected $fillable = [
        'kod',
        'aciklama',
        'marka',
        'stok_miktar',
        'satis_fiyat',
        'satis_doviz',
        'kdv_oran',
        'kategori_id',
        'urun_alt_grup_id',
        'urun_detay_grup_id',
        'prm1',
        'prm2',
        'prm3',
        'prm4',
        'fatura_kodu',
        'resim_yolu',
        'pasif',
        'multi',
        'montaj',
    ];

    protected $casts = [
        'stok_miktar' => 'decimal:3',
        'satis_fiyat' => 'decimal:2',
        'pasif' => 'boolean',
        'multi' => 'boolean',
        'montaj' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'kategori_id');
    }

    public function subGroup()
    {
        return $this->belongsTo(ProductSubGroup::class, 'urun_alt_grup_id');
    }

    public function detailGroup()
    {
        return $this->belongsTo(ProductDetailGroup::class, 'urun_detay_grup_id');
    }
}
