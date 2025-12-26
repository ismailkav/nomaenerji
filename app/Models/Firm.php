<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FirmAuthority;
use App\Models\CariCategory;

class Firm extends Model
{
    use HasFactory;

    protected $table = 'firmalar';

    protected $fillable = [
        'carikod',
        'cari_kategori_id',
        'cariaciklama',
        'adres1',
        'adres2',
        'il',
        'ilce',
        'ulke',
        'telefon',
        'mail',
        'web_sitesi',
        'iskonto1',
        'iskonto2',
        'iskonto3',
        'iskonto4',
        'iskonto5',
        'iskonto6',
    ];

    public function authorities()
    {
        return $this->hasMany(FirmAuthority::class, 'firm_id');
    }

    public function cariKategori()
    {
        return $this->belongsTo(CariCategory::class, 'cari_kategori_id');
    }
}
