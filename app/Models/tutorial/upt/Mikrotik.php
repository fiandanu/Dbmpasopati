<?php

namespace App\Models\tutorial\upt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Mikrotik extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'tutor_mikrotik';

    protected $fillable = [
        'tutor_mikrotik',
        'tipe',
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
