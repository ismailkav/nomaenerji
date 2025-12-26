<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiparisSatirEslestirme extends Model
{
    use HasFactory;

    protected $table = 'siparis_satir_eslestirmeleri';

    protected $fillable = [
        'alim_detay_id',
        'satis_detay_id',
        'miktar',
    ];

    protected $casts = [
        'miktar' => 'decimal:3',
    ];

    public function alimDetay()
    {
        return $this->belongsTo(SiparisDetay::class, 'alim_detay_id');
    }

    public function satisDetay()
    {
        return $this->belongsTo(SiparisDetay::class, 'satis_detay_id');
    }
}

