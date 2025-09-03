<?php

namespace App\Models;

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

    // Cast tanggal_update sebagai date
    protected $casts = [
        'tanggal_update' => 'date',
    ];
}