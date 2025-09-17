<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'nama_provider',
    ];

    protected $dates = [
        'tanggal_update',
    ];

    protected $casts = [
        'tanggal_update' => 'date',
    ];
}
