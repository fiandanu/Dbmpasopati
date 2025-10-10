<?php

namespace App\Models\db;

use App\Models\user\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderPonpesSpp extends Model
{
    use HasFactory;

    protected $table = 'upload_folder_ponpes_spp';

    protected $fillable = [
        'ponpes_id',
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

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }

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
}
