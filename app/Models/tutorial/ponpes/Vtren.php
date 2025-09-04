<?php

namespace App\Models\tutorial\ponpes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Vtren extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'tutor_ponpes_vtren';

    protected $fillable = [
        'tutor_ponpes_vtren',
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
