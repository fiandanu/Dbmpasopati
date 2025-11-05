<?php

namespace App\Models\mclient\catatankartu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user\upt\Upt;

// MEMBENARKAN FILE MODEL DI BAGIAN CATATAN KARTU DARI catatan.php menjadi Catatan.php
class Catatan extends Model
{
    use HasFactory;

    protected $table = 'mclient_catatan';

    protected $fillable = [
        'data_upt_id',
        'spam_vpas_kartu_baru',
        'spam_vpas_kartu_bekas',
        'spam_vpas_kartu_goip',
        'kartu_belum_teregister',
        'whatsapp_telah_terpakai',
        'card_supporting',
        'pic',
        'jumlah_kartu_terpakai_perhari',
        'tanggal',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
    ];

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'data_upt_id');
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('Y-m-d') : null;
    }

    public function getTotalSpamVpasAttribute()
    {
        return (intval($this->spam_vpas_kartu_baru ?? '0')) +
            (intval($this->spam_vpas_kartu_bekas ?? '0')) +
            (intval($this->spam_vpas_kartu_goip ?? '0'));
    }

    public function getTotalKartuTertanganiAttribute()
    {
        return $this->getTotalSpamVpasAttribute();
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('tanggal', now()->year);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('nama_upt', 'LIKE', "%{$term}%")
                ->orWhere('pic', 'LIKE', "%{$term}%");
        });
    }

    public function getCardUtilizationPercentageAttribute()
    {
        $total = intval($this->card_supporting ?? '0');
        $used = intval($this->jumlah_kartu_terpakai_perhari ?? '0');

        if ($total == 0) {
            return 0;
        }

        return round(($used / $total) * 100, 1);
    }

    public function getSummaryTextAttribute()
    {
        $total = $this->getTotalSpamVpasAttribute();
        return "Total {$total} spam tertangani pada " . ($this->tanggal ? $this->tanggal->format('d/m/Y') : 'tanggal tidak diset');
    }
}
