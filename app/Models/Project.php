<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projeler';

    protected $fillable = [
        'kod',
        'pasif',
        'iskonto1',
        'iskonto2',
    ];

    protected $casts = [
        'pasif' => 'boolean',
        'iskonto1' => 'decimal:4',
        'iskonto2' => 'decimal:4',
    ];
}
