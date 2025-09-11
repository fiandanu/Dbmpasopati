<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOpsionalUpt extends Model
{
    use HasFactory;

    protected $table = 'data_opsional_upt';

    protected $fillable = [
        'upt_id',
        'pic_upt',
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
        'akses_topup_pulsa' => 'string',
        'akses_download_rekaman' => 'string',
        'tarif_wartel' => 'decimal:2'
    ];

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id');
    }
}