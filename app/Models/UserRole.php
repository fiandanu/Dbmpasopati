<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class UserRole extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'username',
        'nama',
        'password',
        'password_hint',
        'keterangan',
        'status',
        'role',
        'last_login_at',
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        // Cek apakah sudah di-hash atau belum (hash bcrypt panjangnya 60 karakter dan dimulai dengan $2y$)
        if (strlen($value) === 60 && str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = $value; // Sudah di-hash, langsung simpan
        } else {
            $this->attributes['password'] = Hash::make($value); // Belum di-hash, hash dulu
        }
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isTeknisi()
    {
        return $this->role === 'teknisi';
    }

    public function isMarketing()
    {
        return $this->role === 'marketing';
    }

    public function isActive()
    {
        return $this->status === 'aktif';
    }


    public function getAuthIdentifierName()
    {
        return 'id';
    }
}
