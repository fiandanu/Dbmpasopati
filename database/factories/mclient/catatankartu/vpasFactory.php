<?php

namespace Database\Factories\mclient\catatankartu;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\mclient\catatankartu\Catatan;
use App\Models\User\Upt;
use App\Models\user\Pic;


class VpasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Catatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random UPT VPAS
        $upt = Upt::where('tipe', 'vpas')->inRandomOrder()->first();

        // Get random PIC
        $pic = Pic::inRandomOrder()->first();

        // Random card supporting (bisa juga dari PIC list)
        $cardSupporting = $pic ? $pic->nama_pic : $this->faker->name();

        // Generate random spam numbers
        $kartuBaru = $this->faker->numberBetween(0, 100);
        $kartuBekas = $this->faker->numberBetween(0, 80);
        $kartuGoip = $this->faker->numberBetween(0, 50);

        return [
            'nama_upt' => $upt ? $upt->namaupt : 'UPT ' . $this->faker->city(),
            'spam_vpas_kartu_baru' => (string) $kartuBaru,
            'spam_vpas_kartu_bekas' => (string) $kartuBekas,
            'spam_vpas_kartu_goip' => (string) $kartuGoip,
            'kartu_belum_teregister' => (string) $this->faker->numberBetween(0, 30),
            'whatsapp_telah_terpakai' => (string) $this->faker->numberBetween(0, 50),
            'card_supporting' => $cardSupporting,
            'pic' => $pic ? $pic->nama_pic : $this->faker->name(),
            'jumlah_kartu_terpakai_perhari' => (string) $this->faker->numberBetween(50, 500),
            'tanggal' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif', 'proses', 'pending']),
        ];
    }

    /**
     * Indicate that the catatan is active.
     */
    public function aktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'aktif',
        ]);
    }

    /**
     * Indicate that the catatan is inactive.
     */
    public function nonaktif(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'nonaktif',
        ]);
    }

    /**
     * Indicate that the catatan is in process.
     */
    public function proses(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'proses',
        ]);
    }

    /**
     * Indicate that the catatan is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate high spam activity.
     */
    public function highSpam(): static
    {
        return $this->state(fn(array $attributes) => [
            'spam_vpas_kartu_baru' => (string) $this->faker->numberBetween(100, 300),
            'spam_vpas_kartu_bekas' => (string) $this->faker->numberBetween(80, 200),
            'spam_vpas_kartu_goip' => (string) $this->faker->numberBetween(50, 150),
        ]);
    }

    /**
     * Indicate low spam activity.
     */
    public function lowSpam(): static
    {
        return $this->state(fn(array $attributes) => [
            'spam_vpas_kartu_baru' => (string) $this->faker->numberBetween(0, 10),
            'spam_vpas_kartu_bekas' => (string) $this->faker->numberBetween(0, 10),
            'spam_vpas_kartu_goip' => (string) $this->faker->numberBetween(0, 5),
        ]);
    }

    /**
     * Indicate data for current month.
     */
    public function thisMonth(): static
    {
        return $this->state(fn(array $attributes) => [
            'tanggal' => $this->faker->dateTimeBetween('first day of this month', 'now'),
        ]);
    }

    /**
     * Indicate data for last month.
     */
    public function lastMonth(): static
    {
        return $this->state(fn(array $attributes) => [
            'tanggal' => $this->faker->dateTimeBetween('first day of last month', 'last day of last month'),
        ]);
    }

    /**
     * Indicate data with specific UPT.
     */
    public function forUpt(string $namaUpt): static
    {
        return $this->state(fn(array $attributes) => [
            'nama_upt' => $namaUpt,
        ]);
    }

    /**
     * Indicate data with specific PIC.
     */
    public function forPic(string $picName): static
    {
        return $this->state(fn(array $attributes) => [
            'pic' => $picName,
            'card_supporting' => $picName,
        ]);
    }

    /**
     * Indicate data with no spam (all zeros).
     */
    public function noSpam(): static
    {
        return $this->state(fn(array $attributes) => [
            'spam_vpas_kartu_baru' => '0',
            'spam_vpas_kartu_bekas' => '0',
            'spam_vpas_kartu_goip' => '0',
            'kartu_belum_teregister' => '0',
            'whatsapp_telah_terpakai' => '0',
        ]);
    }

    /**
     * Indicate data with high card utilization.
     */
    public function highUtilization(): static
    {
        return $this->state(fn(array $attributes) => [
            'jumlah_kartu_terpakai_perhari' => (string) $this->faker->numberBetween(400, 1000),
        ]);
    }
}
