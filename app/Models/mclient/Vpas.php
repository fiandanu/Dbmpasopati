<?php

namespace App\Models\mclient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Vpas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mclient_vpas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lokasi',
        'jenis_kendala',
        'detail_kendala',
        'tanggal_terlapor',
        'tanggal_selesai',
        'durasi_hari',
        'status',
        'pic_1',
        'pic_2',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_terlapor' => 'date',
        'tanggal_selesai' => 'date',
        'durasi_hari' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'tanggal_terlapor',
        'tanggal_selesai',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the formatted tanggal terlapor.
     */
    public function getFormattedTanggalTerlaporAttribute()
    {
        return $this->tanggal_terlapor ? $this->tanggal_terlapor->format('d/m/Y') : null;
    }

    /**
     * Get the formatted tanggal selesai.
     */
    public function getFormattedTanggalSelesaiAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('d/m/Y') : null;
    }

    /**
     * Get status badge class for styling.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch (strtolower($this->status ?? '')) {
            case 'selesai':
                return 'badge-success';
            case 'proses':
                return 'badge-warning';
            case 'pending':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get formatted status for display.
     */
    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status ?? 'Belum ditentukan');
    }

    /**
     * Scope untuk filter berdasarkan status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_terlapor', [$startDate, $endDate]);
    }

    /**
     * Scope untuk data bulan ini.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    /**
     * Scope untuk data tahun ini.
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    /**
     * Scope untuk pencarian.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('lokasi', 'LIKE', "%{$term}%")
                ->orWhere('jenis_kendala', 'LIKE', "%{$term}%")
                ->orWhere('status', 'LIKE', "%{$term}%")
                ->orWhere('pic_1', 'LIKE', "%{$term}%")
                ->orWhere('pic_2', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Hitung durasi otomatis berdasarkan tanggal.
     */
    public function calculateDuration()
    {
        if ($this->tanggal_terlapor && $this->tanggal_selesai) {
            $startDate = Carbon::parse($this->tanggal_terlapor);
            $endDate = Carbon::parse($this->tanggal_selesai);
            return $endDate->diffInDays($startDate);
        }

        return null;
    }

    /**
     * Auto calculate duration before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->tanggal_terlapor && $model->tanggal_selesai) {
                $model->durasi_hari = $model->calculateDuration();
            }
        });
    }

    /**
     * Get kendala summary (shortened version).
     */
    public function getKendalaSummaryAttribute()
    {
        if (!$this->jenis_kendala) {
            return 'Tidak ada kendala';
        }

        return strlen($this->jenis_kendala) > 50
            ? substr($this->jenis_kendala, 0, 50) . '...'
            : $this->jenis_kendala;
    }

    /**
     * Check if the issue is resolved.
     */
    public function isResolved()
    {
        return strtolower($this->status ?? '') === 'selesai';
    }

    /**
     * Check if the issue is in progress.
     */
    public function isInProgress()
    {
        return strtolower($this->status ?? '') === 'proses';
    }

    /**
     * Check if the issue is pending.
     */
    public function isPending()
    {
        return strtolower($this->status ?? '') === 'pending';
    }

    /**
     * Get duration text.
     */
    public function getDurationTextAttribute()
    {
        if (!$this->durasi_hari) {
            return '-';
        }

        return $this->durasi_hari . ' hari';
    }
}
