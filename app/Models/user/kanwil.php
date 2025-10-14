<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kanwil extends Model
{
    use HasFactory;

    protected $table = 'kanwil';

    protected $fillable = [
        'kanwil',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function upt()
    {
        return $this->hasMany(Upt::class, 'kanwil_id');
    }


}
