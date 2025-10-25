<?php

namespace App\Models\mclient\ponpes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user\ponpes\Ponpes;
use Carbon\Carbon;

class Reguller extends Model
{
    use HasFactory;

    protected $table = 'mclient_ponpes_reguller';

    protected $fillable = [
        'data_ponpes_id',
        'jenis_kendala',
        'detail_kendala',
        'tanggal_terlapor',
        'tanggal_selesai',
        'durasi_hari',
        'status',
        'pic_1',
        'pic_2',
    ];

    protected $casts = [
        'tanggal_terlapor' => 'date',
        'tanggal_selesai' => 'date',
        'durasi_hari' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'tanggal_terlapor',
        'tanggal_selesai',
        'created_at',
        'updated_at',
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'data_ponpes_id');
    }

    public function getFormattedTanggalTerlaporAttribute()
    {
        return $this->tanggal_terlapor ? $this->tanggal_terlapor->format('Y-m-d') : null;
    }

    public function getFormattedTanggalSelesaiAttribute()
    {
        return $this->tanggal_selesai ? $this->tanggal_selesai->format('Y-m-d') : null;
    }

    public function getStatusBadgeClassAttribute()
    {
        switch (strtolower($this->status ?? '')) {
            case 'selesai':
                return 'badge-success';
            case 'proses':
                return 'badge-warning';
            case 'pending':
                return 'badge-danger';
            case 'terjadwal':
                return 'badge-info';
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
        return $query->whereBetween('tanggal_terlapor', [$startDate, $endDate]);
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
            $q->where('nama_ponpes', 'LIKE', "%{$term}%")
                ->orWhere('nama_wilayah', 'LIKE', "%{$term}%")
                ->orWhere('jenis_kendala', 'LIKE', "%{$term}%")
                ->orWhere('status', 'LIKE', "%{$term}%")
                ->orWhere('pic_1', 'LIKE', "%{$term}%")
                ->orWhere('pic_2', 'LIKE', "%{$term}%");
        });
    }

    public function calculateDuration()
    {
        if ($this->tanggal_terlapor && $this->tanggal_selesai) {
            $startDate = Carbon::parse($this->tanggal_terlapor);
            $endDate = Carbon::parse($this->tanggal_selesai);
            return $endDate->diffInDays($startDate);
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->tanggal_terlapor && $model->tanggal_selesai) {
                $model->durasi_hari = $model->calculateDuration();
            }
        });
    }

    public function getKendalaSummaryAttribute()
    {
        if (!$this->jenis_kendala) {
            return 'Tidak ada kendala';
        }

        return strlen($this->jenis_kendala) > 50
            ? substr($this->jenis_kendala, 0, 50) . '...'
            : $this->jenis_kendala;
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

    public function isScheduled()
    {
        return strtolower($this->status ?? '') === 'terjadwal';
    }

    public function getDurationTextAttribute()
    {
        if (!$this->durasi_hari) {
            return '-';
        }

        return $this->durasi_hari . ' hari';
    }
}