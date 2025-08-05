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
        'uploaded_pdf',
        'tipe',
        'tanggal',
        // 'pdf_folder_1',
        // 'pdf_folder_2',
        // 'pdf_folder_3',
        // 'pdf_folder_4',
        // 'pdf_folder_5',
        // 'pdf_folder_6',
        // 'pdf_folder_7',
        // 'pdf_folder_8',
        // 'pdf_folder_9',
        // 'pdf_folder_10',
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
