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
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi ke data opsional ponpes
    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalPonpes::class, 'ponpes_id');
    }

    // Relasi ke upload folder ponpes
    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderPonpes::class, 'ponpes_id');
    }

    // Accessor untuk mendapatkan semua data termasuk relasi
    public function getAllDataAttribute()
    {
        $baseData = $this->toArray();
        $opsionalData = $this->dataOpsional ? $this->dataOpsional->toArray() : [];
        $uploadData = $this->uploadFolder ? $this->uploadFolder->toArray() : [];
        
        return array_merge($baseData, $opsionalData, $uploadData);
    }
}