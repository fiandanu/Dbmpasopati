<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vpn extends Model
{
    use HasFactory;

    protected $table = 'vpns';

    protected $fillable = [
        'jenis_vpn',
    ];
}
