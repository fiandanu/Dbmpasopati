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

    public function getStatusUpdateAttribute()
    {
        $optionalFields = [
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
            'no_extension',
            'extension_password',
            'pin_tes'
        ];
        $dataOpsional = $this->dataOpsional;
        if (!$dataOpsional) {
            return 'Belum di Update';
        }
        $filledFields = 0;
        foreach ($optionalFields as $field) {
            if (isset($dataOpsional->$field) && $dataOpsional->$field !== null && $dataOpsional->$field !== '') {
                $filledFields++;
            }
        }
        $totalFields = count($optionalFields);
        $percentage = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;

        if ($filledFields == 0) {
            return 'Belum di Update';
        } elseif ($filledFields == $totalFields) {
            return 'Sudah Update';
        } else {
            return "Sebagian ({$percentage}%)";
        }
    }

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id');
    }
}
