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

    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderPonpes::class, 'ponpes_id');
    }

    public function dataOpsional(){
        return $this->hasOne(DataOpsionalPonpes::class, 'ponpes_id');
    }

    public function hasPdfInFolder($folderNumber)
    {
        if (!$this->uploadFolder) {
            return false;
        }

        $column = 'pdf_folder_' . $folderNumber;
        return !empty($this->uploadFolder->$column);
    }

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

    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolder ? $this->uploadFolder->uploaded_pdf : null;
    }

    public function getHasPdfAttribute()
    {
        return $this->uploadFolder && !empty($this->uploadFolder->uploaded_pdf);
    }
}