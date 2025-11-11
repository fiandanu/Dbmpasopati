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
        $this->attributes['password'] = Hash::make($value);
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
