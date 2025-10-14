<?php

namespace App\Models\user;

use App\Models\db\ponpes\DataOpsionalPonpes;
use App\Models\db\ponpes\UploadFolderPonpesPks;
use App\Models\db\ponpes\UploadFolderPonpesSpp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponpes extends Model
{
    use HasFactory;

    protected $table = 'data_ponpes';

    protected $fillable = [
        'nama_ponpes',
        'nama_wilayah_id',
        'tipe',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];


    public function namaWilayah()
    {
        return $this->belongsTo(NamaWilayah::class, 'nama_wilayah_id');
    }

    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalPonpes::class, 'data_ponpes_id');
    }


    public function uploadFolderPks()
    {
        return $this->hasOne(UploadFolderPonpesPks::class, 'data_ponpes_id');
    }

    public function uploadFolderSpp()
    {
        return $this->hasOne(UploadFolderPonpesSpp::class, 'data_ponpes_id');
    }

    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderPonpesSpp::class, 'data_ponpes_id');
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

    public function getUploadedPdfAttribute()
    {
        return $this->uploadFolderPks() ? $this->uploadFolderSpp->pdf_folder_1 : null;
    }

    public function getHasPdfAttribute()
    {
        return $this->uploadFolderSpp && !empty($this->uploadFolderSpp->pdf_folder_1);
    }
}
