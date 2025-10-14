<?php

namespace App\Models\user;

use App\Models\db\ponpes\DataOpsionalPonpes;
use App\Models\db\ponpes\UploadFolderPonpesPks;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\db\ponpes\UploadFolderPonpesSpp;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'data_ponpes';

    protected $fillable = [
        'nama_ponpes',
        'nama_wilayah',
        'tipe',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi ke PKS
    public function uploadFolderPks()
    {
        return $this->hasOne(UploadFolderPonpesPks::class, 'ponpes_id');
    }

    // Relasi ke SPP
    public function uploadFolderSpp()
    {
        return $this->hasOne(UploadFolderPonpesSpp::class, 'ponpes_id');
    }

    // Alias untuk uploadFolder (digunakan di view indexSpp)
    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderPonpesSpp::class, 'ponpes_id');
    }

    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalPonpes::class, 'ponpes_id');
    }

    // Helper methods untuk PDF di folder tertentu (SPP)
    public function hasPdfInFolder($folderNumber)
    {
        if (!$this->uploadFolderSpp) {
            return false;
        }

        $column = 'pdf_folder_' . $folderNumber;
        return !empty($this->uploadFolderSpp->$column);
    }

    public function getPdfFileNameInFolder($folderNumber)
    {
        if (!$this->uploadFolderSpp) {
            return null;
        }

        $column = 'pdf_folder_' . $folderNumber;
        if (!empty($this->uploadFolderSpp->$column)) {
            return basename($this->uploadFolderSpp->$column);
        }

        return null;
    }

    // Hitung berapa folder yang sudah diupload (SPP)
    public function getUploadedFoldersCountAttribute()
    {
        if (!$this->uploadFolderSpp) {
            return 0;
        }

        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($this->uploadFolderSpp->$column)) {
                $count++;
            }
        }

        return $count;
    }

    // Untuk backward compatibility
    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolderSpp ? $this->uploadFolderSpp->pdf_folder_1 : null;
    }

    public function getHasPdfAttribute()
    {
        return $this->uploadFolderSpp && !empty($this->uploadFolderSpp->pdf_folder_1);
    }
}
