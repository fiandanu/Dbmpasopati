<?php

namespace App\Models\mclient\catatankartu;

use App\Models\user\ponpes\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Vtren extends Model
{
    use HasFactory;

    protected $table = 'mclient_catatan_vtren';

    protected $fillable = [
        'nama_ponpes',
        'spam_vtren_kartu_baru',
        'spam_vtren_kartu_bekas',
        'spam_vtren_kartu_goip',
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
        return $this->belongsTo(Ponpes::class, 'nama_ponpes', 'nama_ponpes');
    }

    public function getFormattedTanggalAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('Y-m-d') : null;
    }

    public function getTotalSpamVtrenAttribute()
    {
        return (intval($this->spam_vtren_kartu_baru ?? '0')) + 
               (intval($this->spam_vtren_kartu_bekas ?? '0')) + 
               (intval($this->spam_vtren_kartu_goip ?? '0'));
    }

    public function getTotalKartuTertanganiAttribute()
    {
        return $this->getTotalSpamVtrenAttribute();
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
            $q->where('nama_ponpes', 'LIKE', "%{$term}%")
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
        $total = $this->getTotalSpamVtrenAttribute();
        return "Total {$total} spam tertangani pada " . ($this->tanggal ? $this->tanggal->format('d/m/Y') : 'tanggal tidak diset');
    }
}