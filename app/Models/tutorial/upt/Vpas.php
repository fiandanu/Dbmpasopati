<?php

namespace App\Models\tutorial\upt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Vpas extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'tutor_vpas';

    protected $fillable = [
        'tutor_vpas',
        'tanggal',
        'uploaded_pdf',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
