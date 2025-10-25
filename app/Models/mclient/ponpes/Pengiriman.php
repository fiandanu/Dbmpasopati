<?php

namespace App\Models\mclient\ponpes;

use App\Models\user\ponpes\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'mclient_ponpes_pengiriman';

    protected $fillable = [
        'data_ponpes_id',
        'jenis_layanan',
        'keterangan',
        'tanggal_pengiriman',
        'tanggal_sampai',
        'durasi_hari',
        'pic_1',
        'pic_2',
        'status',
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'date',
        'tanggal_sampai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function ponpes() {
        return $this->belongsTo(Ponpes::class, 'data_ponpes_id');
    }

    // Accessor untuk format jenis layanan
    public function getFormattedJenisLayananAttribute()
    {
        $jenisLayanan = [
            'vtren' => 'VTREN',
            'reguler' => 'Reguler',
            'vtrenreg' => 'VTREN + Reguler'
        ];

        return $jenisLayanan[$this->jenis_layanan] ?? $this->jenis_layanan;
    }

    // Accessor untuk format status
    public function getFormattedStatusAttribute()
    {
        $status = [
            'pending' => 'Pending',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'terjadwal' => 'Terjadwal'
        ];

        return $status[$this->status] ?? ucfirst($this->status);
    }

    // Scope untuk filter berdasarkan jenis layanan
    public function scopeByJenisLayanan($query, $jenisLayanan)
    {
        return $query->where('jenis_layanan', $jenisLayanan);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk data bulan ini
    public function scopeBulanIni($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // Scope untuk data yang sudah selesai
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}