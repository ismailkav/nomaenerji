<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CariCategory extends Model
{
    use HasFactory;

    protected $table = 'cari_kategorileri';

    protected $fillable = [
        'ad',
    ];
}

