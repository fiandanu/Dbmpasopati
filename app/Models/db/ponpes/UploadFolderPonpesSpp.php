<?php

namespace App\Models\db\ponpes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\user\Ponpes;

class UploadFolderPonpesSpp extends Model
{
    use HasFactory;

    // UPDATED: Sesuai dengan nama tabel di migrasi
    protected $table = 'db_ponpes_spp';

    protected $fillable = [
        'data_ponpes_id', // UPDATED: Dari ponpes_id ke data_ponpes_id
        'pdf_folder_1',
        'pdf_folder_2',
        'pdf_folder_3',
        'pdf_folder_4',
        'pdf_folder_5',
        'pdf_folder_6',
        'pdf_folder_7',
        'pdf_folder_8',
        'pdf_folder_9',
        'pdf_folder_10',
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

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Cek apakah PDF di folder tertentu sudah ada
     * @param int $folderNumber (1-10)
     */
    public function hasPdfInFolder($folderNumber)
    {
        if (!in_array($folderNumber, range(1, 10))) {
            return false;
        }

        $column = 'pdf_folder_' . $folderNumber;
        return !empty($this->$column);
    }

    /**
     * Get path PDF di folder tertentu
     * @param int $folderNumber (1-10)
     */
    public function getPdfPath($folderNumber)
    {
        if (!in_array($folderNumber, range(1, 10))) {
            return null;
        }

        $column = 'pdf_folder_' . $folderNumber;
        return $this->$column;
    }

    /**
     * Get nama file PDF di folder tertentu
     * @param int $folderNumber (1-10)
     */
    public function getPdfFileName($folderNumber)
    {
        $path = $this->getPdfPath($folderNumber);
        return $path ? basename($path) : null;
    }

    /**
     * Get semua PDF yang sudah diupload
     * @return array
     */
    public function getPdfFilesAttribute()
    {
        $pdfs = [];
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($this->$column)) {
                $pdfs[$i] = $this->$column;
            }
        }
        return $pdfs;
    }

    /**
     * Hitung berapa folder yang sudah diupload (max 10)
     * @return int
     */
    public function getUploadedFoldersCountAttribute()
    {
        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($this->$column)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Cek apakah semua folder sudah diupload
     * @return bool
     */
    public function isCompleteAttribute()
    {
        return $this->uploaded_folders_count == 10;
    }

    /**
     * Get status upload dalam format string
     * @return string
     */
    public function getUploadStatusAttribute()
    {
        $count = $this->uploaded_folders_count;

        if ($count == 0) {
            return 'Belum Upload';
        } elseif ($count == 10) {
            return 'Sudah Upload Lengkap';
        } else {
            return "Sebagian ({$count}/10)";
        }
    }

    /**
     * Get daftar folder yang belum diupload
     * @return array
     */
    public function getMissingFoldersAttribute()
    {
        $missing = [];
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (empty($this->$column)) {
                $missing[] = $i;
            }
        }
        return $missing;
    }

    /**
     * Get daftar folder yang sudah diupload
     * @return array
     */
    public function getCompletedFoldersAttribute()
    {
        $completed = [];
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($this->$column)) {
                $completed[] = $i;
            }
        }
        return $completed;
    }

    /**
     * Get progress percentage (0-100)
     * @return float
     */
    public function getProgressPercentageAttribute()
    {
        return ($this->uploaded_folders_count / 10) * 100;
    }
}
