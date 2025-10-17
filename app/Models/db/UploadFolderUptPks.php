<?php

namespace App\Models\db;

use App\Models\User\Upt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderUptPks extends Model
{
    use HasFactory;

    protected $table = 'db_upt_pks';

    protected $fillable = [
        'data_upt_id',
        'tanggal_jatuh_tempo',
        'tanggal_kontrak',
        'uploaded_pdf',
    ];

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'data_upt_id');
    }

    // Helper method untuk cek apakah sudah upload
    public function hasUploadedPdf()
    {
        return !empty($this->uploaded_pdf);
    }

    // Get file name
    public function getPdfFileName()
    {
        if (!empty($this->uploaded_pdf)) {
            return basename($this->uploaded_pdf);
        }
        return null;
    }
}
