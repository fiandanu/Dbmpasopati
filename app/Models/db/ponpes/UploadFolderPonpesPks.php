<?php

namespace App\Models\db\ponpes;

use App\Models\user\ponpes\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderPonpesPks extends Model
{
    use HasFactory;

    // UPDATED: Sesuai dengan nama tabel di migrasi
    protected $table = 'db_ponpes_pks';

    protected $fillable = [
        'data_ponpes_id', // UPDATED: Dari ponpes_id ke data_ponpes_id
        'tanggal_kontrak',
        'tanggal_jatuh_tempo',
        'uploaded_pdf_1', // UPDATED: Sekarang ada 2 field
        'uploaded_pdf_2', // BARU: Field untuk folder kedua
    ];

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'tanggal_jatuh_tempo' => 'date',
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
     * @param int $folderNumber (1 atau 2)
     */
    public function hasPdfInFolder($folderNumber)
    {
        if (!in_array($folderNumber, [1, 2])) {
            return false;
        }

        $column = 'uploaded_pdf_' . $folderNumber;
        return !empty($this->$column);
    }

    /**
     * Get path PDF di folder tertentu
     * @param int $folderNumber (1 atau 2)
     */
    public function getPdfPath($folderNumber)
    {
        if (!in_array($folderNumber, [1, 2])) {
            return null;
        }

        $column = 'uploaded_pdf_' . $folderNumber;
        return $this->$column;
    }

    /**
     * Get nama file PDF di folder tertentu
     * @param int $folderNumber (1 atau 2)
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
        for ($i = 1; $i <= 2; $i++) {
            $column = 'uploaded_pdf_' . $i;
            if (!empty($this->$column)) {
                $pdfs[$i] = $this->$column;
            }
        }
        return $pdfs;
    }

    /**
     * Hitung berapa PDF yang sudah diupload (max 2)
     * @return int
     */
    public function getUploadedPdfCountAttribute()
    {
        $count = 0;
        for ($i = 1; $i <= 2; $i++) {
            $column = 'uploaded_pdf_' . $i;
            if (!empty($this->$column)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Cek apakah semua PDF sudah diupload
     * @return bool
     */
    public function isCompleteAttribute()
    {
        return !empty($this->uploaded_pdf_1) && !empty($this->uploaded_pdf_2);
    }

    /**
     * Get status upload dalam format string
     * @return string
     */
    public function getUploadStatusAttribute()
    {
        $count = $this->uploaded_pdf_count;

        if ($count == 0) {
            return 'Belum Upload';
        } elseif ($count == 2) {
            return 'Sudah Upload (2/2)';
        } else {
            return "Sudah Upload (1/2)";
        }
    }

    // ==========================================
    // BACKWARD COMPATIBILITY
    // ==========================================

    /**
     * Accessor untuk uploaded_pdf (backward compatibility)
     * Mengambil dari uploaded_pdf_1
     * @deprecated Gunakan uploaded_pdf_1 atau uploaded_pdf_2
     */
    public function getUploadedPdfAttribute($value)
    {
        // Jika kolom uploaded_pdf ada di database (untuk backward compatibility)
        if (isset($this->attributes['uploaded_pdf'])) {
            return $this->attributes['uploaded_pdf'];
        }

        // Jika tidak, return uploaded_pdf_1
        return $this->attributes['uploaded_pdf_1'] ?? null;
    }

    /**
     * Mutator untuk uploaded_pdf (backward compatibility)
     * @deprecated Gunakan uploaded_pdf_1 atau uploaded_pdf_2
     */
    public function setUploadedPdfAttribute($value)
    {
        $this->attributes['uploaded_pdf_1'] = $value;
    }
}
