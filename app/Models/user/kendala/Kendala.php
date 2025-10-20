<?php

namespace App\Models\user\kendala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendala extends Model
{
    use HasFactory;

    protected $table = 'kendala';

    protected $fillable = [
        'jenis_kendala',
    ];

    protected $dates = [
        'tanggal_update',
    ];

    protected $casts = [
        'tanggal_update' => 'date',
    ];
}
