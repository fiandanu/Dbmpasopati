<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\Pengiriman;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PengirimanFactory extends Factory
{
    protected $model = Pengiriman::class;

    public function definition(): array
    {
        $tanggalPengiriman = $this->faker->dateTimeBetween('-3 months', 'now');
        $durasiHari = $this->faker->numberBetween(1, 14);
        $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");
        
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
            'tanggal_pengiriman' => $tanggalPengiriman,
            'tanggal_sampai' => $this->faker->optional(0.7)->passthrough($tanggalSampai),
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
            'tanggal_pengiriman' => null,
            'tanggal_sampai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function terjadwal(): static
    {
        $tanggalPengiriman = $this->faker->dateTimeBetween('now', '+1 month');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'terjadwal',
            'tanggal_pengiriman' => $tanggalPengiriman,
            'tanggal_sampai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function proses(): static
    {
        $tanggalPengiriman = $this->faker->dateTimeBetween('-1 week', 'now');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'proses',
            'tanggal_pengiriman' => $tanggalPengiriman,
            'tanggal_sampai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function selesai(): static
    {
        $tanggalPengiriman = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $durasiHari = $this->faker->numberBetween(1, 14);
        $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");
        
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'tanggal_pengiriman' => $tanggalPengiriman,
            'tanggal_sampai' => $tanggalSampai,
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