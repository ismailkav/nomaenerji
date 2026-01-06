<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiyatListesi extends Model
{
    use HasFactory;

    protected $table = 'fiyat_listeleri';

    protected $fillable = [
        'firm_id',
        'baslangic_tarihi',
        'bitis_tarihi',
        'hazirlayan',
    ];

    protected $casts = [
        'baslangic_tarihi' => 'date',
        'bitis_tarihi' => 'date',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class, 'firm_id');
    }

    public function detaylar()
    {
        return $this->hasMany(FiyatListesiDetay::class, 'fiyat_listesi_id');
    }
}

