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
        'stok_miktar',
        'satis_fiyat',
        'kdv_oran',
        'kategori_id',
        'resim_yolu',
        'pasif',
    ];

    protected $casts = [
        'stok_miktar' => 'decimal:3',
        'satis_fiyat' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'kategori_id');
    }
}
