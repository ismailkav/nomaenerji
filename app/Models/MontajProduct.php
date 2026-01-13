<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MontajProduct extends Model
{
    use HasFactory;

    protected $table = 'montaj_urunleri';

    protected $fillable = [
        'montaj_grup_id',
        'urun_id',
        'urun_kod',
        'birim',
        'birim_fiyat',
        'doviz',
        'sirano',
    ];

    protected $casts = [
        'birim_fiyat' => 'float',
        'sirano' => 'integer',
    ];

    public function montajGrup()
    {
        return $this->belongsTo(MontajGroup::class, 'montaj_grup_id');
    }

    public function urun()
    {
        return $this->belongsTo(Product::class, 'urun_id');
    }
}

