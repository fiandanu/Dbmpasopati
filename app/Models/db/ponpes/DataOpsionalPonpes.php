<?php

namespace App\Models\db\ponpes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user\Ponpes;

class DataOpsionalPonpes extends Model
{
    use HasFactory;

    // UPDATED: Sesuai dengan nama tabel di migrasi
    protected $table = 'db_opsional_ponpes';

    protected $fillable = [
        'data_ponpes_id', // UPDATED: Dari ponpes_id ke data_ponpes_id
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
        'jumlah_extension',
        'pin_tes',
        'no_extension',
        'extension_password',
        'no_pemanggil',
        'email_airdroid',
        'password',
        // BARU: Dari migrasi
        'vpns_id',
        'provider_id',
    ];

    protected $casts = [
        'status_wartel' => 'string', // UPDATED: Di migrasi tipe string, bukan boolean
        'akses_topup_pulsa' => 'string',
        'akses_download_rekaman' => 'string',
        'tarif_wartel' => 'decimal:2',
        'jumlah_wbp' => 'integer',
        'jumlah_line' => 'integer',
        'jumlah_extension' => 'integer',
    ];

    /**
     * Relasi ke tabel data_ponpes
     * UPDATED: Menggunakan data_ponpes_id
     */
    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'data_ponpes_id');
    }

    /**
     * Alias relasi untuk backward compatibility
     * @deprecated Gunakan ponpes()
     */
    public function dataPonpes()
    {
        return $this->ponpes();
    }

    /**
     * Relasi ke tabel vpns (jika ada)
     * BARU: Sesuai migrasi
     */
    public function vpn()
    {
        return $this->belongsTo(\App\Models\user\Vpn::class, 'vpns_id');
    }

    /**
     * Relasi ke tabel providers (jika ada)
     * BARU: Sesuai migrasi
     */
    public function provider()
    {
        return $this->belongsTo(\App\Models\user\Provider::class, 'provider_id');
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Cek apakah data opsional lengkap
     * @return bool
     */
    public function isCompleteAttribute()
    {
        $requiredFields = [
            'pic_ponpes',
            'no_telpon',
            'alamat',
            'jumlah_wbp',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get persentase kelengkapan data
     * @return float
     */
    public function getCompletenessPercentageAttribute()
    {
        $allFields = [
            'pic_ponpes',
            'no_telpon',
            'alamat',
            'jumlah_wbp',
            'jumlah_line',
            'provider_internet',
            'kecepatan_internet',
            'tarif_wartel',
            'status_wartel',
            'internet_protocol',
            'vpn_user',
            'vpn_password',
            'jumlah_extension',
        ];

        $filledFields = 0;
        foreach ($allFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        return ($filledFields / count($allFields)) * 100;
    }

    /**
     * Format tarif wartel dengan currency
     * @return string
     */
    public function getFormattedTarifWartelAttribute()
    {
        if (empty($this->tarif_wartel)) {
            return 'Rp 0';
        }

        return 'Rp ' . number_format($this->tarif_wartel, 0, ',', '.');
    }

    /**
     * Accessor untuk status wartel yang user-friendly
     * @return string
     */
    public function getStatusWartelLabelAttribute()
    {
        if (empty($this->status_wartel)) {
            return 'Tidak Aktif';
        }

        return ucfirst($this->status_wartel);
    }
}
