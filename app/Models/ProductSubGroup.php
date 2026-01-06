<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubGroup extends Model
{
    use HasFactory;

    protected $table = 'urun_alt_gruplari';

    protected $fillable = [
        'urun_grup_id',
        'ad',
    ];

    public function group()
    {
        return $this->belongsTo(ProductCategory::class, 'urun_grup_id');
    }
}

