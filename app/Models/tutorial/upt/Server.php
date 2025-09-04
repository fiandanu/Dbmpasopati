<?php

namespace App\Models\tutorial\upt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Server extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'tutor_server';

    protected $fillable = [
        'tutor_server',
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
