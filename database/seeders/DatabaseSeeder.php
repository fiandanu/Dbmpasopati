<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user\Kanwil;
use App\Models\user\Upt;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kanwil dulu
        $kanwilData = [
            ['kanwil' => 'Kanwil Banten'],
            ['kanwil' => 'Kanwil DKI Jakarta'],
            ['kanwil' => 'Kanwil Jawa Barat'],
            ['kanwil' => 'Kanwil Jawa Tengah'],
            ['kanwil' => 'Kanwil Jawa Timur'],
            ['kanwil' => 'Kanwil Kalimantan'],
            ['kanwil' => 'Kanwil Sulawesi'],
            ['kanwil' => 'Kanwil Sumatera'],
        ];

        foreach ($kanwilData as $kanwil) {
            Kanwil::create($kanwil);
        }

        // 2. Baru buat UPT
        Upt::factory()->count(100)->create();
    }
}
