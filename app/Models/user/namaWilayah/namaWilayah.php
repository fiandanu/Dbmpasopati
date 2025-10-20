<?php

namespace App\Models\user\namaWilayah;

use App\Models\user\ponpes\Ponpes;
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

    public function dataPonpes()
    {
        return $this->hasMany(Ponpes::class, 'nama_wilayah_id');
    }

    /**
     * Alias untuk relasi dataPonpes
     */
    public function ponpes()
    {
        return $this->dataPonpes();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope untuk mencari wilayah berdasarkan nama
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('nama_wilayah', 'LIKE', "%{$term}%");
    }

    /**
     * Scope untuk wilayah yang memiliki ponpes
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasPonpes($query)
    {
        return $query->whereHas('dataPonpes');
    }

    /**
     * Scope untuk ordering berdasarkan nama
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('nama_wilayah', $direction);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Accessor untuk menampilkan nama wilayah dengan format yang konsisten
     * @return string
     */
    public function getFormattedNameAttribute()
    {
        return ucwords(strtolower($this->nama_wilayah));
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Hitung jumlah ponpes di wilayah ini
     * @return int
     */
    public function getPonpesCountAttribute()
    {
        return $this->dataPonpes()->count();
    }

    /**
     * Hitung jumlah ponpes PKS di wilayah ini
     * @return int
     */
    public function getPonpesPksCountAttribute()
    {
        return $this->dataPonpes()->whereHas('dbPonpesPks')->count();
    }

    /**
     * Hitung jumlah ponpes SPP di wilayah ini
     * @return int
     */
    public function getPonpesSppCountAttribute()
    {
        return $this->dataPonpes()->whereHas('dbPonpesSpp')->count();
    }

    /**
     * Get ponpes dengan tipe tertentu
     * @param string $tipe
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPonpesByTipe($tipe)
    {
        return $this->dataPonpes()->where('tipe', $tipe)->get();
    }
}
