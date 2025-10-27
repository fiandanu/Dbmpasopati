<?php

namespace App\Models\user\upt;

use App\Models\db\upt\DataOpsionalUpt;
use App\Models\db\upt\UploadFolderUptPks;
use App\Models\db\upt\UploadFolderUptSpp;
use App\Models\mclient\catatankartu\Catatan;
use App\Models\user\kanwil\Kanwil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upt extends Model
{
    use HasFactory;

    protected $table = 'data_upt';

    protected $fillable = [
        'namaupt',
        'kanwil_id',
        'tipe',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function kanwil()
    {
        return $this->belongsTo(Kanwil::class, 'kanwil_id');
    }

    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalUpt::class, 'data_upt_id');
    }

    public function uploadFolderPks()
    {
        return $this->hasOne(UploadFolderUptPks::class, 'data_upt_id');
    }

    public function uploadFolderSpp()
    {
        return $this->hasOne(UploadFolderUptSpp::class, 'data_upt_id');
    }

    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderUptSpp::class, 'data_upt_id');
    }

    // public function CatatanVpas() {
    //     return $this->hasMany(Catatan::class, 'data_upt_id');
    // }

    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolderPks ? $this->uploadFolderPks->uploaded_pdf : null;
    }

    public function getHasPdfAttribute()
    {
        return $this->uploadFolderPks && !empty($this->uploadFolderPks->uploaded_pdf);
    }

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
};
