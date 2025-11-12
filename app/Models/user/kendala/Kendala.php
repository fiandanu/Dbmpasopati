<?php

namespace App\Models\user\kendala;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendala extends Model
{
    use HasFactory;

    protected $table = 'kendala';

    protected $fillable = [
        'jenis_kendala',
    ];

    protected $dates = [
        'tanggal_update',
    ];

    protected $casts = [
        'tanggal_update' => 'date',
    ];

    // Tambahkan relasi ini
    public function vtrenRecords(): HasMany
    {
        return $this->hasMany(\App\Models\mclient\ponpes\Vtren::class, 'kendala_id');
    }

    public function vpasRecords(): HasMany
    {
        return $this->hasMany(\App\Models\mclient\Vpas::class, 'kendala_id');
    }

    public function regullerRecords(): HasMany
    {
        return $this->hasMany(\App\Models\mclient\Reguller::class, 'kendala_id');
    }

    
}