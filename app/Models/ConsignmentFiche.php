<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentFiche extends Model
{
    use HasFactory;

    protected $table = 'konsinye_fisleri';

    protected $fillable = [
        'tip',
        'fis_sira',
        'fis_no',
        'tarih',
        'cari_id',
        'carikod',
        'cariaciklama',
        'depo_id',
        'teslim_tarihi',
        'durum',
        'aciklama',
        'hazirlayan_user_id',
        'proje_id',
        'islem_tarihi',
    ];

    protected $casts = [
        'tarih' => 'date',
        'teslim_tarihi' => 'date',
        'islem_tarihi' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(ConsignmentFicheLine::class, 'konsinye_fis_id');
    }

    public function cari()
    {
        return $this->belongsTo(Firm::class, 'cari_id');
    }

    public function proje()
    {
        return $this->belongsTo(Project::class, 'proje_id');
    }

    public function hazirlayan()
    {
        return $this->belongsTo(User::class, 'hazirlayan_user_id');
    }

    public function depo()
    {
        return $this->belongsTo(Depot::class, 'depo_id');
    }
}
