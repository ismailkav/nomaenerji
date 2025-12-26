<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmAuthority extends Model
{
    use HasFactory;

    protected $table = 'firma_yetkilileri';

    protected $fillable = [
        'firm_id',
        'full_name',
        'email',
        'phone',
        'role',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class, 'firm_id');
    }
}

