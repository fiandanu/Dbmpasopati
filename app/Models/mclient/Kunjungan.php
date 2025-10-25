<?php

namespace App\Models\mclient;

use App\Models\user\kanwil\Kanwil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user\upt\Upt;
use Carbon\Carbon;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'mclient_kunjungan';

    protected $fillable = [
        'data_upt_id',
        'jenis_layanan',
        'keterangan',
        'jadwal',
        'tanggal_selesai',
        'durasi_hari',
        'pic_1',
        'pic_2',
        'status',
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
        return $this->belongsTo(Upt::class, 'data_upt_id');
    }


    public function getFormattedJadwalAttribute()
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

    public function getJenisLayananBadgeClassAttribute()
    {
        switch (strtolower($this->jenis_layanan ?? '')) {
            case 'vpas':
                return 'badge-primary';
            case 'reguler':
                return 'badge-secondary';
            case 'vpasreg':
                return 'badge-info';
            default:
                return 'badge-light';
        }
    }

    public function getFormattedJenisLayananAttribute()
    {
        switch (strtolower($this->jenis_layanan ?? '')) {
            case 'vpas':
                return 'VPAS';
            case 'reguler':
                return 'Reguler';
            case 'vpasreg':
                return 'VPAS + Reguler';
            default:
                return 'Belum ditentukan';
        }
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByJenisLayanan($query, $jenisLayanan)
    {
        return $query->where('jenis_layanan', $jenisLayanan);
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
                ->orWhere('jenis_layanan', 'LIKE', "%{$term}%")
                ->orWhere('keterangan', 'LIKE', "%{$term}%")
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

    public function getKeteranganSummaryAttribute()
    {
        if (!$this->keterangan) {
            return 'Tidak ada keterangan';
        }

        return strlen($this->keterangan) > 50
            ? substr($this->keterangan, 0, 50) . '...'
            : $this->keterangan;
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

    public function isVpasOnly()
    {
        return strtolower($this->jenis_layanan ?? '') === 'vpas';
    }

    public function isRegulerOnly()
    {
        return strtolower($this->jenis_layanan ?? '') === 'reguler';
    }

    public function isVpasReguler()
    {
        return strtolower($this->jenis_layanan ?? '') === 'vpasreg';
    }
}