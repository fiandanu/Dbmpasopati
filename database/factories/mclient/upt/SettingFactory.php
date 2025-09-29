<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\SettingAlat;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SettingFactory extends Factory
{
    protected $model = SettingAlat::class;

    public function definition(): array
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-3 months', 'now');
        $durasiHari = $this->faker->numberBetween(1, 30);
        $tanggalSelesai = (clone $tanggalTerlapor)->modify("+{$durasiHari} days");
        
        $jenisLayanan = $this->faker->randomElement(['vpas', 'reguler', 'vpasreg']);
        $status = $this->faker->randomElement(['pending', 'terjadwal', 'proses', 'selesai']);

        return [
            'nama_upt' => $this->faker->randomElement([
                'UPT Balai Metrologi Surabaya',
                'UPT Balai Metrologi Semarang',
                'UPT Balai Metrologi Bandung',
                'UPT Balai Metrologi Yogyakarta',
                'UPT Balai Metrologi Malang',
                'UPT Balai Metrologi Solo',
                'UPT Balai Metrologi Madiun',
                'UPT Balai Metrologi Purwokerto',
            ]),
            'jenis_layanan' => $jenisLayanan,
            'keterangan' => $this->faker->optional(0.8)->paragraph(2),
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $this->faker->optional(0.7)->passthrough($tanggalSelesai),
            'durasi_hari' => $this->faker->optional(0.7)->passthrough($durasiHari),
            'pic_1' => $this->faker->name(),
            'pic_2' => $this->faker->optional(0.6)->name(),
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function terjadwal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terjadwal',
        ]);
    }

    public function proses(): static
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-2 weeks', 'now');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'proses',
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function selesai(): static
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $durasiHari = $this->faker->numberBetween(1, 30);
        $tanggalSelesai = (clone $tanggalTerlapor)->modify("+{$durasiHari} days");
        
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => $durasiHari,
        ]);
    }

    public function vpas(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'vpas',
        ]);
    }

    public function reguler(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'reguler',
        ]);
    }

    public function vpasReguler(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'vpasreg',
        ]);
    }
}