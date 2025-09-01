<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Relasi ke UploadFolderPonpes
    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderPonpes::class, 'ponpes_id');
    }

    // Accessor untuk mengecek apakah PDF sudah ada di folder tertentu
    public function hasPdfInFolder($folderNumber)
    {
        if (!$this->uploadFolder) {
            return false;
        }

        $column = 'pdf_folder_' . $folderNumber;
        return !empty($this->uploadFolder->$column);
    }

    // Accessor untuk mendapatkan nama file PDF di folder tertentu
    public function getPdfFileNameInFolder($folderNumber)
    {
        if (!$this->uploadFolder) {
            return null;
        }

        $column = 'pdf_folder_' . $folderNumber;
        if (!empty($this->uploadFolder->$column)) {
            return basename($this->uploadFolder->$column);
        }

        return null;
    }

    // Accessor untuk menghitung berapa folder yang sudah memiliki PDF
    public function getUploadedFoldersCountAttribute()
    {
        if (!$this->uploadFolder) {
            return 0;
        }

        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $column = 'pdf_folder_' . $i;
            if (!empty($this->uploadFolder->$column)) {
                $count++;
            }
        }

        return $count;
    }

    // Accessor untuk mendapatkan status upload PDF
    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolder ? $this->uploadFolder->uploaded_pdf : null;
    }

    // Accessor untuk cek apakah PDF sudah diupload
    public function getHasPdfAttribute()
    {
        return $this->uploadFolder && !empty($this->uploadFolder->uploaded_pdf);
    }
}