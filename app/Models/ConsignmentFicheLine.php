<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentFicheLine extends Model
{
    use HasFactory;

    protected $table = 'konsinye_fis_satirlari';

    protected $fillable = [
        'konsinye_fis_id',
        'stokkod',
        'stokaciklama',
        'miktar',
        'iade_miktar',
        'durum',
    ];

    protected $casts = [
        'miktar' => 'decimal:4',
        'iade_miktar' => 'decimal:4',
    ];

    public function fiche()
    {
        return $this->belongsTo(ConsignmentFiche::class, 'konsinye_fis_id');
    }
}

