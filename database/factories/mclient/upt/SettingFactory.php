<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\SettingAlat;
use App\Models\user\upt\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = SettingAlat::class;

    public function definition(): array
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-6 months', 'now');
        $tanggalSelesai = $this->faker->optional(0.7)->dateTimeBetween($tanggalTerlapor, '+30 days');

        // Hitung durasi jika tanggal selesai ada
        $durasiHari = null;
        if ($tanggalSelesai) {
            $durasiHari = (new \DateTime($tanggalSelesai->format('Y-m-d')))
                ->diff(new \DateTime($tanggalTerlapor->format('Y-m-d')))
                ->days;
        }

        return [
            'data_upt_id' => Upt::factory(),
            'jenis_layanan' => $this->faker->randomElement(['vpas', 'reguler', 'vpasreg']),
            'keterangan' => $this->faker->optional(0.8)->paragraph(),
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => $durasiHari,
            'pic_1' => $this->faker->name(),
            'pic_2' => $this->faker->optional(0.6)->name(),
            'status' => $this->faker->randomElement(['selesai', 'proses', 'pending', 'tertanggal_terlapor']),
        ];
    }

    /**
     * State untuk status selesai
     */
    public function selesai(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'selesai',
            'tanggal_selesai' => $this->faker->dateTimeBetween($attributes['tanggal_terlapor'], 'now'),
        ]);
    }

    /**
     * State untuk status proses
     */
    public function proses(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'proses',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk status pending
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk jenis layanan VPAS
     */
    public function vpas(): static
    {
        return $this->state(fn(array $attributes) => [
            'jenis_layanan' => 'vpas',
        ]);
    }

    /**
     * State untuk jenis layanan Reguler
     */
    public function reguler(): static
    {
        return $this->state(fn(array $attributes) => [
            'jenis_layanan' => 'reguler',
        ]);
    }

    /**
     * State untuk jenis layanan VPAS + Reguler
     */
    public function vpasReg(): static
    {
        return $this->state(fn(array $attributes) => [
            'jenis_layanan' => 'vpasreg',
        ]);
    }

    /**
     * State untuk data dengan UPT yang sudah ada
     */
    public function forUpt(Upt $upt): static
    {
        return $this->state(fn(array $attributes) => [
            'data_upt_id' => $upt->id,
        ]);
    }


}
