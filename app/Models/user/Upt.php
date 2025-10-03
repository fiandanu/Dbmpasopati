<?php

namespace App\Models\user;

use App\Models\db\DataOpsionalUpt;
use App\Models\db\UploadFolderUpt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upt extends Model
{
    use HasFactory;

    protected $table = 'data_upt';

    protected $fillable = [
        'namaupt',
        'kanwil',
        'tipe',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalUpt::class, 'upt_id');
    }

    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderUpt::class, 'upt_id');
    }

    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolder ? $this->uploadFolder->uploaded_pdf : null;
    }

    public function getHasPdfAttribute()
    {
        return $this->uploadFolder && !empty($this->uploadFolder->uploaded_pdf);
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
}
