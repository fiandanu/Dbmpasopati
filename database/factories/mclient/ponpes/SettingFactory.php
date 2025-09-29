<?php

namespace Database\Factories\Mclient\Ponpes;

use App\Models\mclient\ponpes\SettingPonpes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SettingFactory extends Factory
{
    protected $model = SettingPonpes::class;

    public function definition(): array
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-3 months', 'now');
        $tanggalSelesai = $this->faker->optional(0.7)->dateTimeBetween($tanggalTerlapor, '+1 month');
        
        $jenisLayanan = $this->faker->randomElement(['vtren', 'reguler', 'vtrenreg']);
        $status = $this->faker->randomElement(['pending', 'terjadwal', 'proses', 'selesai']);

        $durasiHari = null;
        if ($tanggalSelesai) {
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
        }

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
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => $durasiHari,
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
        return $this->state(fn (array $attributes) => [
            'status' => 'proses',
            'tanggal_terlapor' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    public function selesai(): static
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-2 months', '-1 week');
        $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
        
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai)),
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