<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\user\Kanwil;
use App\Models\User\NamaWilayah;

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

        // 2. Buat NamaWilayah
        $wilayahData = [
            ['nama_wilayah' => 'Kanwil Banten'],
            ['nama_wilayah' => 'Kanwil DKI Jakarta'],
            ['nama_wilayah' => 'Kanwil Jawa Barat'],
            ['nama_wilayah' => 'Kanwil Jawa Tengah'],
            ['nama_wilayah' => 'Kanwil Jawa Timur'],
            ['nama_wilayah' => 'Kanwil Kalimantan'],
            ['nama_wilayah' => 'Kanwil Sulawesi'],
            ['nama_wilayah' => 'Kanwil Sumatera'],
        ];

        foreach ($wilayahData as $wilayah) {
            NamaWilayah::create($wilayah);
        }

        // 3. Baru buat UPT
        // Upt::factory()->count(100)->create();
    }
}
