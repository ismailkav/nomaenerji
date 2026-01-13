<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontajGroup extends Model
{
    use HasFactory;

    protected $table = 'montaj_gruplari';

    protected $fillable = [
        'kod',
        'urun_detay_grup_id',
        'sirano',
    ];

    protected $casts = [
        'sirano' => 'integer',
    ];

    public function urunDetayGrup()
    {
        return $this->belongsTo(ProductDetailGroup::class, 'urun_detay_grup_id');
    }
}
