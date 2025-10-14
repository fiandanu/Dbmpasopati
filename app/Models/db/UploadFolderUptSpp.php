<?php

namespace App\Models\db;

use App\Models\user\Upt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderUptSpp extends Model
{
    use HasFactory;

    protected $table = 'db_upt_spp';

    protected $fillable = [
        'data_upt_id',
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

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'data_upt_id');
    }

    // Get all PDF files as array
    public function getPdfFilesAttribute()
    {
        $pdfs = [];
        for ($i = 1; $i <= 10; $i++) {
            $pdfField = "pdf_folder_$i";
            if ($this->$pdfField) {
                $pdfs[] = $this->$pdfField;
            }
        }
        return $pdfs;
    }

    // Helper method untuk cek berapa folder yang sudah diupload
    public function getUploadedFoldersCountAttribute()
    {
        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $pdfField = "pdf_folder_$i";
            if (!empty($this->$pdfField)) {
                $count++;
            }
        }
        return $count;
    }

    // Check if specific folder has PDF
    public function hasPdfInFolder($folderNumber)
    {
        $column = 'pdf_folder_' . $folderNumber;
        return !empty($this->$column);
    }

    // Get PDF filename in specific folder
    public function getPdfFileNameInFolder($folderNumber)
    {
        $column = 'pdf_folder_' . $folderNumber;
        if (!empty($this->$column)) {
            return basename($this->$column);
        }
        return null;
    }
}
