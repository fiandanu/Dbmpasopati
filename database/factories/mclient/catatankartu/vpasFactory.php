<?php

namespace Database\Factories\mclient\catatankartu;

use App\Models\mclient\catatankartu\Catatan;
use App\Models\user\upt\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class VpasFactory extends Factory
{
    protected $model = Catatan::class;

    public function definition(): array
    {
        // Pastikan UPT VPAS ada
        $upt = Upt::where('tipe', 'vpas')->inRandomOrder()->first();
        if (!$upt) {
            $upt = Upt::factory()->create([
                'tipe' => 'vpas',
                'namaupt' => 'UPT VPAS ' . fake()->city(),
            ]);
        }

        $kartuBaru = $this->faker->numberBetween(0, 100);
        $kartuBekas = $this->faker->numberBetween(0, 80);
        $kartuGoip = $this->faker->numberBetween(0, 50);

        return [
            'data_upt_id' => $upt->id, // foreign key
            'spam_vpas_kartu_baru' => $kartuBaru,
            'spam_vpas_kartu_bekas' => $kartuBekas,
            'spam_vpas_kartu_goip' => $kartuGoip,
            'kartu_belum_teregister' => $this->faker->numberBetween(0, 30),
            'whatsapp_telah_terpakai' => $this->faker->numberBetween(0, 50),
            'card_supporting' => $this->faker->numberBetween(100, 1000), // total kartu
            'pic' => $this->faker->name(),
            'jumlah_kartu_terpakai_perhari' => $this->faker->numberBetween(50, 500),
            'tanggal' => Carbon::instance($this->faker->dateTimeBetween('-6 months', 'now')),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif', 'proses', 'pending']),
        ];
    }

    // === STATE METHODS ===

    public function aktif(): static
    {
        return $this->state(fn() => ['status' => 'aktif']);
    }

    public function nonaktif(): static
    {
        return $this->state(fn() => ['status' => 'nonaktif']);
    }

    public function proses(): static
    {
        return $this->state(fn() => ['status' => 'proses']);
    }

    public function pending(): static
    {
        return $this->state(fn() => ['status' => 'pending']);
    }

    public function highSpam(): static
    {
        return $this->state(fn() => [
            'spam_vpas_kartu_baru' => $this->faker->numberBetween(100, 300),
            'spam_vpas_kartu_bekas' => $this->faker->numberBetween(80, 200),
            'spam_vpas_kartu_goip' => $this->faker->numberBetween(50, 150),
        ]);
    }

    public function lowSpam(): static
    {
        return $this->state(fn() => [
            'spam_vpas_kartu_baru' => $this->faker->numberBetween(0, 10),
            'spam_vpas_kartu_bekas' => $this->faker->numberBetween(0, 10),
            'spam_vpas_kartu_goip' => $this->faker->numberBetween(0, 5),
        ]);
    }

    public function noSpam(): static
    {
        return $this->state(fn() => [
            'spam_vpas_kartu_baru' => 0,
            'spam_vpas_kartu_bekas' => 0,
            'spam_vpas_kartu_goip' => 0,
            'kartu_belum_teregister' => 0,
            'whatsapp_telah_terpakai' => 0,
        ]);
    }

    public function highUtilization(): static
    {
        return $this->state(fn() => [
            'jumlah_kartu_terpakai_perhari' => $this->faker->numberBetween(400, 1000),
            'card_supporting' => $this->faker->numberBetween(500, 1200),
        ]);
    }

    public function thisMonth(): static
    {
        return $this->state(fn() => [
            'tanggal' => $this->faker->dateTimeBetween('first day of this month', 'now'),
        ]);
    }

    public function lastMonth(): static
    {
        return $this->state(fn() => [
            'tanggal' => $this->faker->dateTimeBetween('first day of last month', 'last day of last month'),
        ]);
    }

    public function forUpt(string $namaUpt): static
    {
        $upt = Upt::firstOrCreate(
            ['namaupt' => $namaUpt],
            ['tipe' => 'vpas']
        );

        return $this->state(fn() => [
            'data_upt_id' => $upt->id,
        ]);
    }

    public function withPic(string $picName): static
    {
        return $this->state(fn() => [
            'pic' => $picName,
        ]);
    }

    public function withCardSupporting(int $total): static
    {
        return $this->state(fn() => [
            'card_supporting' => $total,
        ]);
    }
}