<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'ponpes';

    protected $fillable = [
        'nama_ponpes',
        'nama_wilayah',
        'tanggal',
    ];
}