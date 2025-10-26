<?php

namespace Database\Factories;

use App\Models\db\upt\DataOpsionalUpt;
use App\Models\user\upt\Upt;
use App\Models\user\vpn\Vpn;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataOpsionalUptFactory extends Factory
{
    protected $model = DataOpsionalUpt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['Telkom', 'Indihome', 'MyRepublic', 'Biznet', 'First Media', 'XL', 'Telkomsel'];
        $kecepatanOptions = ['10 Mbps', '20 Mbps', '50 Mbps', '100 Mbps', '200 Mbps'];
        $aksesOptions = ['Ya', 'Tidak', 'Terbatas'];

        // Ambil UPT yang sudah ada dari database secara random
        $upt = Upt::inRandomOrder()->first();

        // Ambil VPN yang sudah ada dari database secara random
        $vpn = Vpn::inRandomOrder()->first();

        return [
            'data_upt_id' => $upt ? $upt->id : Upt::factory(),
            'vpns_id' => $vpn ? $vpn->id : Vpn::factory(),
            'pic_upt' => $this->faker->name,
            'no_telpon' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'jumlah_wbp' => $this->faker->numberBetween(50, 2000),
            'jumlah_line' => $this->faker->numberBetween(2, 20),
            'provider_internet' => $this->faker->randomElement($providers),
            'kecepatan_internet' => $this->faker->randomElement($kecepatanOptions),
            'tarif_wartel' => $this->faker->randomFloat(2, 500, 5000),
            'status_wartel' => $this->faker->boolean(80), // 80% kemungkinan true
            'akses_topup_pulsa' => $this->faker->randomElement($aksesOptions),
            'password_topup' => $this->faker->password(8, 16),
            'akses_download_rekaman' => $this->faker->randomElement($aksesOptions),
            'password_download' => $this->faker->password(8, 16),
            'internet_protocol' => $this->faker->localIpv4 . '/' . $this->faker->numberBetween(24, 30),
            'vpn_user' => $this->faker->userName,
            'vpn_password' => $this->faker->password(8, 16),
            'jumlah_extension' => $this->faker->numberBetween(1, 50),
            'pin_tes' => $this->faker->numerify('####'),
            'no_extension' => $this->faker->numerify('###'),
            'extension_password' => $this->faker->numerify('######'),
            'no_pemanggil' => $this->faker->numerify('###'),
            'email_airdroid' => $this->faker->email,
            'password' => $this->faker->password(8, 16),
        ];
    }

    /**
     * State untuk UPT dengan wartel aktif
     */
    public function wartelAktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status_wartel' => true,
            'tarif_wartel' => $this->faker->randomFloat(2, 1000, 3000),
            'jumlah_line' => $this->faker->numberBetween(5, 15),
        ]);
    }

    /**
     * State untuk UPT dengan wartel non-aktif
     */
    public function wartelNonAktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status_wartel' => false,
            'tarif_wartel' => 0,
            'jumlah_line' => 0,
        ]);
    }

    /**
     * State untuk UPT dengan akses lengkap
     */
    public function aksesLengkap(): static
    {
        return $this->state(fn(array $attributes) => [
            'akses_topup_pulsa' => 'Ya',
            'akses_download_rekaman' => 'Ya',
            'status_wartel' => true,
        ]);
    }

    /**
     * State untuk UPT dengan akses terbatas
     */
    public function aksesTerbatas(): static
    {
        return $this->state(fn(array $attributes) => [
            'akses_topup_pulsa' => 'Tidak',
            'akses_download_rekaman' => 'Tidak',
            'password_topup' => null,
            'password_download' => null,
        ]);
    }

    /**
     * State untuk UPT kecil (WBP sedikit)
     */
    public function uptKecil(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(50, 200),
            'jumlah_line' => $this->faker->numberBetween(2, 5),
            'jumlah_extension' => $this->faker->numberBetween(1, 10),
        ]);
    }

    /**
     * State untuk UPT besar (WBP banyak)
     */
    public function uptBesar(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(1000, 2000),
            'jumlah_line' => $this->faker->numberBetween(10, 20),
            'jumlah_extension' => $this->faker->numberBetween(30, 50),
            'kecepatan_internet' => $this->faker->randomElement(['100 Mbps', '200 Mbps']),
        ]);
    }

    /**
     * State untuk data dengan field terisi sebagian (untuk testing status update)
     */
    public function dataSebagia(): static
    {
        return $this->state(fn(array $attributes) => [
            'vpn_user' => null,
            'vpn_password' => null,
            'email_airdroid' => null,
            'password' => null,
            'pin_tes' => null,
        ]);
    }

    /**
     * State untuk data kosong (untuk testing status "Belum di Update")
     */
    public function dataKosong(): static
    {
        return $this->state(fn(array $attributes) => [
            'pic_upt' => null,
            'no_telpon' => null,
            'alamat' => null,
            'jumlah_wbp' => null,
            'jumlah_line' => null,
            'provider_internet' => null,
            'kecepatan_internet' => null,
            'tarif_wartel' => null,
            'status_wartel' => null,
            'akses_topup_pulsa' => null,
            'password_topup' => null,
            'akses_download_rekaman' => null,
            'password_download' => null,
            'internet_protocol' => null,
            'vpn_user' => null,
            'vpn_password' => null,
            'jumlah_extension' => null,
            'pin_tes' => null,
            'no_extension' => null,
            'extension_password' => null,
            'no_pemanggil' => null,
            'email_airdroid' => null,
            'password' => null,
        ]);
    }
}
