<?php

namespace App\Models\db;

use App\Models\user\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderPonpes extends Model
{
    use HasFactory;

    protected $table = 'upload_folder_ponpes';

    protected $fillable = [
        'ponpes_id',
        'tanggal_kontrak',
        'tanggal_jatuh_tempo',
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

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'tanggal_jatuh_tempo' => 'date',
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
}
