<?php

namespace App\Models\user\pic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\mclient\Kunjungan;

class Pic extends Model
{
    use HasFactory;

    protected $table = 'pic';

    protected $fillable = [
        'nama_pic',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan kunjungan sebagai PIC 1
    public function kunjunganAsPic1()
    {
        return $this->hasMany(Kunjungan::class, 'pic_1', 'nama_pic');
    }

    // Relationship dengan kunjungan sebagai PIC 2
    public function kunjunganAsPic2()
    {
        return $this->hasMany(Kunjungan::class, 'pic_2', 'nama_pic');
    }

    // Get all kunjungan where this PIC is involved
    public function getAllKunjungan()
    {
        return Kunjungan::where('pic_1', $this->nama_pic)
            ->orWhere('pic_2', $this->nama_pic)
            ->get();
    }

    // Scope untuk mencari PIC berdasarkan nama
    public function scopeSearch($query, $term)
    {
        return $query->where('nama_pic', 'LIKE', "%{$term}%");
    }

    // Accessor untuk menampilkan nama PIC dengan format yang konsisten
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->nama_pic));
    }
}
