<?php

namespace App\Models\mclient;

use App\Models\user\Upt;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_reguller';

    protected $fillable = [
        'nama_upt',
        'kanwil',
        'jenis_layanan',
        'keterangan',
        'jadwal',
        'tanggal_selesai',
        'durasi_hari',
        'status',
        'pic_1',
        'pic_2',
    ];

    protected $casts = [
        'jadwal' => 'date',
        'tanggal_selesai' => 'date',
        'durasi_hari' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'jadwal',
        'tanggal_selesai',
        'created_at',
        'updated_at',
    ];

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'nama_upt', 'namaupt');
    }

    public function getFormattedTanggalTerlaporAttribute()
    {
        return $this->jadwal ? $this->jadwal->format('Y-m-d') : null;
    }

public function getFormattedTanggalSelesaiAttribute()
{
    return $this->tanggal_selesai ? $this->tanggal_selesai->format('Y-m-d') : null;
}


    public function getStatusBadgeClassAttribute()
    {
        switch (strtolower($this->status ?? '')) {
            case 'terjadwal':
                return 'badge-info';
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

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status ?? 'Belum ditentukan');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('jadwal', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nama_upt', 'LIKE', "%{$term}%")
                ->orWhere('kanwil', 'LIKE', "%{$term}%")
                ->orWhere('jenis_layanan', 'LIKE', "%{$term}%")
                ->orWhere('status', 'LIKE', "%{$term}%")
                ->orWhere('pic_1', 'LIKE', "%{$term}%")
                ->orWhere('pic_2', 'LIKE', "%{$term}%");
        });
    }

    public function calculateDuration()
    {
        if ($this->jadwal && $this->tanggal_selesai) {
            $startDate = Carbon::parse($this->jadwal);
            $endDate = Carbon::parse($this->tanggal_selesai);
            return $endDate->diffInDays($startDate);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->jadwal && $model->tanggal_selesai) {
                $model->durasi_hari = $model->calculateDuration();
            }
        });
    }

    public function getKendalaSummaryAttribute()
    {
        if (!$this->jenis_layanan) {
            return 'Tidak ada kendala';
        }

        return strlen($this->jenis_layanan) > 50
            ? substr($this->jenis_layanan, 0, 50) . '...'
            : $this->jenis_layanan;
    }

    public function isResolved()
    {
        return strtolower($this->status ?? '') === 'selesai';
    }

    public function isInProgress()
    {
        return strtolower($this->status ?? '') === 'proses';
    }

    public function isPending()
    {
        return strtolower($this->status ?? '') === 'pending';
    }

    public function getDurationTextAttribute()
    {
        if (!$this->durasi_hari) {
            return '-';
        }

        return $this->durasi_hari . ' hari';
    }
        public function isScheduled()
    {
        return strtolower($this->status ?? '') === 'terjadwal';
    }

}