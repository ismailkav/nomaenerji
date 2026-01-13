<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontajProductGroup extends Model
{
    use HasFactory;

    protected $table = 'montaj_urun_gruplari';

    protected $fillable = [
        'montaj_grup_id',
        'montaj_urun_id',
        'urun_detay_grup_id',
        'sirano',
    ];

    protected $casts = [
        'sirano' => 'integer',
    ];

    public function montajGrup()
    {
        return $this->belongsTo(MontajGroup::class, 'montaj_grup_id');
    }

    public function montajUrun()
    {
        return $this->belongsTo(MontajProduct::class, 'montaj_urun_id');
    }

    public function urunDetayGrup()
    {
        return $this->belongsTo(ProductDetailGroup::class, 'urun_detay_grup_id');
    }
}
