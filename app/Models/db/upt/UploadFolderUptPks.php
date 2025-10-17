<?php

namespace App\Models\db\upt;

use App\Models\user\Upt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFolderUptPks extends Model
{
    use HasFactory;

    protected $table = 'db_upt_pks';

    protected $fillable = [
        'data_upt_id',
        'tanggal_kontrak',
        'tanggal_jatuh_tempo',
        'uploaded_pdf_1',
        'uploaded_pdf_2',
    ];

    protected $casts = [
        'tanggal_kontrak' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function upt()
    {
        return $this->belongsTo(Upt::class, 'data_upt_id');
    }
}
