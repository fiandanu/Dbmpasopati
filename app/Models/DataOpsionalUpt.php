<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOpsionalUpt extends Model
{
    use HasFactory;

    // Perbaikan: gunakan nama tabel yang sesuai dengan migration
    protected $table = 'data_opsional_upt';

    protected $fillable = [
        'upt_id',
        'pic_upt',
        'no_telpon',
        'alamat',
        'jumlah_wbp',
        'jumlah_line_reguler',
        'provider_internet',
        'kecepatan_internet',
        'tarif_wartel_reguler',
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
        'tarif_wartel_reguler' => 'decimal:2'
    ];

    // Relasi balik ke UPT
    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id');
    }
}