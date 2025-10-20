<?php

namespace App\Models\user\vpn;

use App\Models\db\upt\DataOpsionalUpt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vpn extends Model
{
    use HasFactory;

    protected $table = 'vpns';

    protected $fillable = [
        'jenis_vpn',
    ];


    public function dataOpsionalUpts()
    {
        return $this->hasMany(DataOpsionalUpt::class, 'vpns_id');
    }
}
