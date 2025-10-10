<?php

namespace App\Models\db;

use App\Models\user\Ponpes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderPonpesPks extends Model
{
    use HasFactory;

    protected $table = 'upload_folder_ponpes_pks';

    protected $fillable = [
        'ponpes_id',
        'tanggal_kontrak',
        'tanggal_jatuh_tempo',
        'uploaded_pdf',
    ];

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function ponpes()
    {
        return $this->belongsTo(Ponpes::class, 'ponpes_id');
    }
}
