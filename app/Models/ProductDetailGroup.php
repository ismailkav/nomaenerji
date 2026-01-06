<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetailGroup extends Model
{
    use HasFactory;

    protected $table = 'urun_detay_gruplari';

    protected $fillable = [
        'urun_grup_id',
        'urun_alt_grup_id',
        'ad',
    ];

    public function group()
    {
        return $this->belongsTo(ProductCategory::class, 'urun_grup_id');
    }

    public function subGroup()
    {
        return $this->belongsTo(ProductSubGroup::class, 'urun_alt_grup_id');
    }
}

