<?php

namespace Database\Seeders;

use App\Models\mclient\ponpes\Kunjungan;
use App\Models\mclient\ponpes\Pengiriman;
use App\Models\mclient\ponpes\Reguller;
use App\Models\mclient\SettingAlat;
use App\Models\mclient\Vpas;
use Illuminate\Database\Seeder;
use App\Models\user\kanwil\Kanwil;
use App\Models\user\kendala\Kendala;
use App\Models\user\NamaWilayah\NamaWilayah;
use App\Models\user\pic\Pic;
use App\Models\user\provider\Provider;

use App\Models\user\vpn\Vpn;
use Database\Factories\DataOpsionalPonpesFactory;
use Database\Factories\DataOpsionalUptFactory;


// UNTUK BAGIAN MCLIENT UPT
use Database\Factories\Mclient\Upt\VpasFactory;
use Database\Factories\Mclient\Upt\KunjunganFactory;
use Database\Factories\Mclient\Upt\PengirimanFactory;
use Database\Factories\Mclient\Upt\SettingFactory;
use Database\Factories\Mclient\Upt\RegullerFactory;
// 
use Database\Factories\user\PonpesFactory;
use Database\Factories\user\UptFactory;
use Dflydev\DotAccessData\Data;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

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


        $kendalaData = [
            ['jenis_kendala' => 'Alat Rusak'],
            ['jenis_kendala' => 'Jaringan Lemot '],
            ['jenis_kendala' => 'Tidak ada sinyal '],
            ['jenis_kendala' => 'Alat meledak'],
            ['jenis_kendala' => 'Kebanyakan duit'],
            ['jenis_kendala' => 'Beli lambogrhini inden 6 bulan'],
            ['jenis_kendala' => 'Beli inova bekas'],
            ['jenis_kendala' => 'Mesin v8 harus di service'],
        ];

        foreach ($kendalaData as $kendala) {
            Kendala::create($kendala);
        }


        $picData = [
            ['nama_pic' => 'akim'],
            ['nama_pic' => 'danzo'],
            ['nama_pic' => 'budi'],
            ['nama_pic' => 'bambang'],
            ['nama_pic' => 'yudi'],
            ['nama_pic' => 'anto'],
            ['nama_pic' => 'gagap'],
            ['nama_pic' => 'atenk'],
        ];

        foreach ($picData as $pic) {
            Pic::create($pic);
        }


        $providerData = [
            ['nama_provider' => 'Telkomsel'],
            ['nama_provider' => 'Indosat'],
            ['nama_provider' => 'Smartfren'],
            ['nama_provider' => 'XL'],
            ['nama_provider' => 'Indihome'],
            ['nama_provider' => 'First Media'],
            ['nama_provider' => 'First Media'],
            ['nama_provider' => 'Biznet'],
            ['nama_provider' => 'Oxygen.id'],
            ['nama_provider' => 'XL Home'],
        ];

        foreach ($providerData as $provider) {
            Provider::create($provider);
        }

        $vpnData = [
            ['jenis_vpn' => 'Nord VPN'],
            ['jenis_vpn' => 'Express VPN'],
            ['jenis_vpn' => 'Surfshark VPN'],
            ['jenis_vpn' => 'Cyber Ghost'],
            ['jenis_vpn' => 'Proton VPN'],
            ['jenis_vpn' => 'IPVanish VPN'],
            ['jenis_vpn' => 'Hostpot Sheild'],
            ['jenis_vpn' => 'TunnelBear '],
            ['jenis_vpn' => 'Proton VPN '],
            ['jenis_vpn' => 'Atlas VPN'],
        ];

        foreach ($vpnData as $vpn) {
            Vpn::create($vpn);
        }

        // URUTAN PERTAMA HARUS MEMBUAT UPT FACTORY DULU
        UptFactory::new()->count(50)->create();
        PonpesFactory::new()->count(50)->create();

        // URUTAN KEDUA MEMBUAT DATA OPSIONAL UPT FACTORY
        DataOpsionalUptFactory::new()->count(50)->create();
        DataOpsionalPonpesFactory::new()->count(50)->create();

        // URUTAN KETIGA MEMBUAT MCLIENT UPT FACTORY
        VpasFactory::new()->count(200)->create(); 
        RegullerFactory::new()->count(20)->create(); 
        KunjunganFactory::new()->count(20)->create(); 
        SettingFactory::new()->count(20)->create(); 
        PengirimanFactory::new()->count(20)->create();
        
        // URUTAN KE EMPAT MEMBUAT MCLIENT PONPES FACTORY
        

    }
}
