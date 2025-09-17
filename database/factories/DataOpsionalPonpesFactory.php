<?php

namespace Database\Factories;

use App\Models\db\DataOpsionalPonpes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\db\DataOpsionalPonpes>
 */
class DataOpsionalPonpesFactory extends Factory
{
    protected $model = DataOpsionalPonpes::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['Telkom', 'Indihome', 'MyRepublic', 'Biznet', 'First Media', 'XL', 'Telkomsel'];
        $kecepatanOptions = ['10 Mbps', '20 Mbps', '50 Mbps', '100 Mbps', '200 Mbps'];
        $jenisVpnOptions = ['OpenVPN', 'L2TP', 'PPTP', 'IKEv2', 'WireGuard'];

        return [
            'ponpes_id' => null, // Will be set by parent factory or manually
            'pic_ponpes' => $this->faker->name,
            'no_telpon' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'jumlah_wbp' => $this->faker->numberBetween(20, 500), // Ponpes biasanya lebih sedikit dari lapas
            'jumlah_line' => $this->faker->numberBetween(1, 10),
            'provider_internet' => $this->faker->randomElement($providers),
            'kecepatan_internet' => $this->faker->randomElement($kecepatanOptions),
            'tarif_wartel' => $this->faker->randomFloat(2, 1000, 3000),
            'status_wartel' => $this->faker->boolean(70), // 70% kemungkinan true
            'akses_topup_pulsa' => $this->faker->boolean(60),
            'password_topup' => $this->faker->password(8, 16),
            'akses_download_rekaman' => $this->faker->boolean(50),
            'password_download' => $this->faker->password(8, 16),
            'internet_protocol' => $this->faker->localIpv4 . '/' . $this->faker->numberBetween(24, 30),
            'vpn_user' => $this->faker->userName,
            'vpn_password' => $this->faker->password(8, 16),
            'jenis_vpn' => $this->faker->randomElement($jenisVpnOptions),
            'jumlah_extension' => $this->faker->numberBetween(1, 20),
            'pin_tes' => $this->faker->numerify('####'),
            'no_extension' => $this->faker->numerify('###'),
            'extension_password' => $this->faker->numerify('######'),
        ];
    }

    /**
     * State untuk ponpes dengan wartel aktif
     */
    public function wartelAktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status_wartel' => true,
            'tarif_wartel' => $this->faker->randomFloat(2, 1500, 2500),
            'jumlah_line' => $this->faker->numberBetween(3, 8),
        ]);
    }

    /**
     * State untuk ponpes dengan wartel non-aktif
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
     * State untuk ponpes dengan akses lengkap
     */
    public function aksesLengkap(): static
    {
        return $this->state(fn(array $attributes) => [
            'akses_topup_pulsa' => true,
            'akses_download_rekaman' => true,
            'status_wartel' => true,
        ]);
    }

    /**
     * State untuk ponpes dengan akses terbatas
     */
    public function aksesTerbatas(): static
    {
        return $this->state(fn(array $attributes) => [
            'akses_topup_pulsa' => false,
            'akses_download_rekaman' => false,
            'password_topup' => null,
            'password_download' => null,
        ]);
    }

    /**
     * State untuk ponpes kecil (santri sedikit)
     */
    public function ponpesKecil(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(20, 100),
            'jumlah_line' => $this->faker->numberBetween(1, 3),
            'jumlah_extension' => $this->faker->numberBetween(1, 5),
            'kecepatan_internet' => $this->faker->randomElement(['10 Mbps', '20 Mbps']),
        ]);
    }

    /**
     * State untuk ponpes besar (santri banyak)
     */
    public function ponpesBesar(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(300, 500),
            'jumlah_line' => $this->faker->numberBetween(6, 10),
            'jumlah_extension' => $this->faker->numberBetween(15, 20),
            'kecepatan_internet' => $this->faker->randomElement(['100 Mbps', '200 Mbps']),
        ]);
    }

    /**
     * State untuk ponpes tipe reguler
     */
    public function reguler(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(50, 200),
            'provider_internet' => $this->faker->randomElement(['Telkom', 'Indihome']),
            'status_wartel' => $this->faker->boolean(80), // Reguler lebih sering punya wartel
        ]);
    }

    /**
     * State untuk ponpes tipe vtren (modern)
     */
    public function vtren(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_wbp' => $this->faker->numberBetween(100, 400),
            'provider_internet' => $this->faker->randomElement(['MyRepublic', 'Biznet', 'First Media']),
            'kecepatan_internet' => $this->faker->randomElement(['50 Mbps', '100 Mbps', '200 Mbps']),
            'akses_topup_pulsa' => true,
            'akses_download_rekaman' => true,
            'status_wartel' => true,
        ]);
    }

    /**
     * State untuk ponpes dengan internet terbatas
     */
    public function internetTerbatas(): static
    {
        return $this->state(fn(array $attributes) => [
            'kecepatan_internet' => $this->faker->randomElement(['10 Mbps', '20 Mbps']),
            'provider_internet' => 'Telkom',
            'akses_download_rekaman' => false,
        ]);
    }

    /**
     * State untuk ponpes modern dengan fasilitas lengkap
     */
    public function modern(): static
    {
        return $this->state(fn(array $attributes) => [
            'provider_internet' => $this->faker->randomElement(['Biznet', 'MyRepublic', 'First Media']),
            'kecepatan_internet' => $this->faker->randomElement(['100 Mbps', '200 Mbps']),
            'status_wartel' => true,
            'akses_topup_pulsa' => true,
            'akses_download_rekaman' => true,
            'jumlah_extension' => $this->faker->numberBetween(10, 20),
        ]);
    }

    /**
     * State untuk ponpes tradisional
     */
    public function tradisional(): static
    {
        return $this->state(fn(array $attributes) => [
            'provider_internet' => 'Telkom',
            'kecepatan_internet' => $this->faker->randomElement(['10 Mbps', '20 Mbps']),
            'status_wartel' => $this->faker->boolean(60),
            'akses_topup_pulsa' => $this->faker->boolean(40),
            'akses_download_rekaman' => $this->faker->boolean(30),
            'jumlah_extension' => $this->faker->numberBetween(1, 8),
        ]);
    }
}
