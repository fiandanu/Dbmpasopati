<?php

namespace Database\Factories\Mclient\Ponpes;

use App\Models\mclient\ponpes\Kunjungan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class KunjunganFactory extends Factory
{
    protected $model = Kunjungan::class;

    public function definition(): array
    {
        $jadwal = $this->faker->dateTimeBetween('-3 months', '+2 months');
        $tanggalSelesai = (clone $jadwal)->modify('+' . $this->faker->numberBetween(1, 30) . ' days');
        
        $jenisLayanan = $this->faker->randomElement(['vtren', 'reguler', 'vtrenreg']);
        $status = $this->faker->randomElement(['pending', 'terjadwal', 'proses', 'selesai']);

        return [
            'nama_ponpes' => $this->faker->randomElement([
                'Pondok Pesantren Al-Hikmah',
                'Pondok Pesantren Darul Ulum',
                'Pondok Pesantren Al-Falah',
                'Pondok Pesantren Nurul Jadid',
                'Pondok Pesantren Tebuireng',
                'Pondok Pesantren Lirboyo',
                'Pondok Pesantren Gontor',
                'Pondok Pesantren Langitan',
            ]),
            'jenis_layanan' => $jenisLayanan,
            'keterangan' => $this->faker->optional(0.8)->paragraph(2),
            'jadwal' => $jadwal,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => Carbon::parse($jadwal)->diffInDays(Carbon::parse($tanggalSelesai)),
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
        ]);
    }

    public function terjadwal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'terjadwal',
            'jadwal' => $this->faker->dateTimeBetween('now', '+1 month'),
        ]);
    }

    public function proses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'proses',
            'jadwal' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'jadwal' => $this->faker->dateTimeBetween('-2 months', '-1 week'),
            'tanggal_selesai' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    public function vtren(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'vtren',
        ]);
    }

    public function reguler(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'reguler',
        ]);
    }

    public function vtrenReguler(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_layanan' => 'vtrenreg',
        ]);
    }
}