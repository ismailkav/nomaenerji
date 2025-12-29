<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockFiche extends Model
{
    use HasFactory;

    protected $table = 'stok_fisleri';

    protected $fillable = [
        'tip',
        'fis_sira',
        'fis_no',
        'tarih',
        'depo_id',
        'cikis_depo_id',
        'giris_depo_id',
        'aciklama',
        'hazirlayan_user_id',
        'islem_tarihi',
    ];

    protected $casts = [
        'tarih' => 'date',
        'islem_tarihi' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(StockFicheLine::class, 'stok_fis_id');
    }

    public function depo()
    {
        return $this->belongsTo(Depot::class, 'depo_id');
    }

    public function cikisDepo()
    {
        return $this->belongsTo(Depot::class, 'cikis_depo_id');
    }

    public function girisDepo()
    {
        return $this->belongsTo(Depot::class, 'giris_depo_id');
    }

    public function hazirlayan()
    {
        return $this->belongsTo(User::class, 'hazirlayan_user_id');
    }
}

