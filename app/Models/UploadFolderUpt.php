<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk Upload Folder UPT
class UploadFolderUpt extends Model
{
    use HasFactory;

    protected $table = 'upload_folder_upt';

    protected $fillable = [
        'upt_id',
        'uploaded_pdf',
        'pdf_folder_1',
        'pdf_folder_2',
        'pdf_folder_3',
        'pdf_folder_4',
        'pdf_folder_5',
        'pdf_folder_6',
        'pdf_folder_7',
        'pdf_folder_8',
        'pdf_folder_9',
        'pdf_folder_10'
    ];

    // Relasi balik ke UPT
    public function upt()
    {
        return $this->belongsTo(Upt::class, 'upt_id');
    }

    // Accessor untuk mendapatkan semua PDF yang ada
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
}

// Model untuk Upload Folder Ponpes
