<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOpsionalPonpes extends Model
{
    use HasFactory;

    protected $table = 'data_opsional_ponpes';

    protected $fillable = [
        'ponpes_id',
        'pic_ponpes',
        'no_telpon',
        'alamat',
        'jumlah_wbp',
        'jumlah_line',
        'provider_internet',
        'kecepatan_internet',
        'tarif_wartel',
        'status_wartel',
        'akses_topup_pulsa',
        'password_topup',
        'akses_download_rekaman',
        'password_download',
        'internet_protocol',
        'vpn_user',
        'vpn_password',
        'jenis_vpn',
        'jumlah_extension',
        'pin_tes',
        'no_extension',
        'extension_password'
    ];

    protected $casts = [
        'status_wartel' => 'boolean',
        'akses_topup_pulsa' => 'boolean',
        'akses_download_rekaman' => 'boolean',
        'tarif_wartel' => 'decimal:2'
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }
}