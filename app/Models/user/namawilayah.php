<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NamaWilayah extends Model
{
    use HasFactory;

    protected $table = 'nama_wilayah';

    protected $fillable = [
        'nama_wilayah',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope untuk mencari wilayah berdasarkan nama
    public function scopeSearch($query, $term)
    {
        return $query->where('nama_wilayah', 'LIKE', "%{$term}%");
    }

    // Accessor untuk menampilkan nama wilayah dengan format yang konsisten
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->nama_wilayah));
    }
}
