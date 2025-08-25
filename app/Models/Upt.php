<?php

namespace App\Models;

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

    // Relasi ke DataOpsionalUpt
    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalUpt::class, 'upt_id');
    }

    // Relasi ke UploadFolderUpt
    public function uploadFolder()
    {
        return $this->hasOne(UploadFolderUpt::class, 'upt_id');
    }
}