<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'ponpes';

    protected $fillable = [
        'nama_ponpes',
        'nama_wilayah',
        'tanggal',
        'pic_ponpes',
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
        'extension_password',
        'pin_tes',
        'jumlah_extension',
        'no_extension'
    ];
}
