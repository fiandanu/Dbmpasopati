<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'nama_provider',
        'jenis_vpn',
        'tanggal_update',
    ];

    protected $dates = [
        'tanggal_update',
    ];

    // Cast tanggal_update sebagai date
    protected $casts = [
        'tanggal_update' => 'date',
    ];
}