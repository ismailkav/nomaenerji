<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDefinition extends Model
{
    use HasFactory;

    protected $table = 'formlar';

    protected $fillable = [
        'ekran',
        'dosya_ad',
        'gorunen_isim',
    ];
}

