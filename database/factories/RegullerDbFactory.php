<?php

namespace Database\Factories;

use App\Models\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class RegullerDbFactory extends Factory
{

    protected $model = Upt::class;
    public function definition(): array
    {
        return [
            // Field Wajib Form UPT
            'namaupt' => 'UPT ' . $this->faker->city(),
            'kanwil' => $this->faker->randomElement([
                'Kanwil Jakarta',
                'Kanwil Bandung',
                'Kanwil Surabaya',
                'Kanwil Medan',
                'Kanwil Makassar',
                'Kanwil Denpasar',
                'Kanwil Semarang',
                'Kanwil Palembang',
                'Kanwil Banjarmasin',
                'Kanwil Jayapura'
            ]),
            'tipe' => $this->faker->randomElement([
                'Reguler',
                'Vpas'
            ]),
            'tanggal' => now(),

            // Data Opsional (Form VPAS)
            'pic_upt' => $this->faker->name(),
            'no_telpon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'jumlah_wbp' => $this->faker->numberBetween(50, 2000),
            'jumlah_line_reguler' => $this->faker->numberBetween(5, 50),
            'provider_internet' => $this->faker->randomElement([
                'Telkom',
                'Indihome',
                'Biznet',
                'MNC Play',
                'First Media',
                'MyRepublic'
            ]),
            'kecepatan_internet' => $this->faker->randomElement([
                '10 Mbps',
                '20 Mbps',
                '50 Mbps',
                '100 Mbps',
                '200 Mbps'
            ]),
            'tarif_wartel_reguler' => $this->faker->numberBetween(1000, 5000),
            'status_wartel' => $this->faker->randomElement([
                'Aktif',
                'Non-Aktif',
                'Maintenance',
                'Pending'
            ]),

            // IMC PAS
            'akses_topup_pulsa' => $this->faker->randomElement([
                'Ya',
                'Tidak'
            ]),
            'password_topup' => Hash::make('password123'),
            'akses_download_rekaman' => $this->faker->url(),
            'password_download' => Hash::make('download123'),

            // AKSES VPN
            'internet_protocol' => $this->faker->ipv4(),
            'vpn_user' => $this->faker->userName(),
            'vpn_password' => Hash::make('vpn123'),
            'jenis_vpn' => $this->faker->randomElement([
                'OpenVPN',
                'L2TP',
                'PPTP',
                'IKEv2',
                'WireGuard'
            ]),

            // Extension Reguler
            'jumlah_extension' => $this->faker->numberBetween(10, 100),
            'pin_tes' => $this->faker->numberBetween(1000, 9999),
            'no_extension' => $this->generateExtensionNumbers(),
            'extension_password' => $this->generateExtensionPasswords(),
        ];
    }

    /**
     * Generate extension numbers as comma-separated string
     */
    private function generateExtensionNumbers(): string
    {
        $extensions = [];
        $count = $this->faker->numberBetween(5, 20);

        for ($i = 0; $i < $count; $i++) {
            $extensions[] = $this->faker->numberBetween(1000, 9999);
        }

        return implode(',', $extensions);
    }

    /**
     * Generate extension passwords as comma-separated string
     */
    private function generateExtensionPasswords(): string
    {
        $passwords = [];
        $count = $this->faker->numberBetween(5, 20);

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = $this->faker->password(6, 12);
        }

        return implode(',', $passwords);
    }

    /**
     * State for UPT with minimal data (only required fields)
     */
    public function minimal(): static
    {
        return $this->state(fn(array $attributes) => [
            'pic_upt' => null,
            'no_telpon' => null,
            'alamat' => null,
            'jumlah_wbp' => null,
            'jumlah_line_reguler' => null,
            'provider_internet' => null,
            'kecepatan_internet' => null,
            'tarif_wartel_reguler' => null,
            'status_wartel' => null,
            'akses_topup_pulsa' => null,
            'password_topup' => null,
            'akses_download_rekaman' => null,
            'password_download' => null,
            'internet_protocol' => null,
            'vpn_user' => null,
            'vpn_password' => null,
            'jenis_vpn' => null,
            'jumlah_extension' => null,
            'pin_tes' => null,
            'no_extension' => null,
            'extension_password' => null,
        ]);
    }

    /**
     * State for active UPT with wartel
     */
    public function activeWartel(): static
    {
        return $this->state(fn(array $attributes) => [
            'status_wartel' => 'Aktif',
            'tarif_wartel_reguler' => $this->faker->numberBetween(2000, 4000),
            'jumlah_line_reguler' => $this->faker->numberBetween(10, 30),
        ]);
    }

    /**
     * State for UPT with VPN access
     */
    public function withVpnAccess(): static
    {
        return $this->state(fn(array $attributes) => [
            'internet_protocol' => $this->faker->ipv4(),
            'vpn_user' => $this->faker->userName(),
            'vpn_password' => Hash::make('vpnaccess123'),
            'jenis_vpn' => $this->faker->randomElement(['OpenVPN', 'L2TP', 'IKEv2']),
        ]);
    }

    /**
     * State for UPT with high capacity
     */
    public function highCapacity(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(1000, 2500),
            'jumlah_line_reguler' => $this->faker->numberBetween(30, 60),
            'jumlah_extension' => $this->faker->numberBetween(50, 150),
            'kecepatan_internet' => $this->faker->randomElement(['100 Mbps', '200 Mbps', '500 Mbps']),
        ]);
    }
}
