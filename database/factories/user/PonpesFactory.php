<?php

namespace Database\Factories\user;

use App\Models\user\ponpes\Ponpes;
use App\Models\user\namaWilayah\NamaWilayah;
use Illuminate\Database\Eloquent\Factories\Factory;

class PonpesFactory extends Factory
{
    protected $model = Ponpes::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $NamaWilayahOptions = [
            'Banten',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'Jawa Timur',
            'Kalimantan',
            'Sulawesi',
            'Sumatera'
        ];

        $tipeOptions = [
            'reguler',
            'vtren',
        ];

        return [
            'nama_ponpes' => 'Pondok ' . $this->faker->company . ' ' . $this->faker->city,
            'nama_wilayah_id' => NamaWilayah::inRandomOrder()->first()?->id ?? NamaWilayah::factory(),
            'tipe' => $this->faker->randomElement($tipeOptions),
            'tanggal' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * State untuk tipe PKS
     */
    public function pks(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'PKS',
            'nama_ponpes' => 'Ponpes PKS ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk tipe SPP
     */
    public function spp(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'SPP',
            'nama_ponpes' => 'Ponpes SPP ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk tipe Mandiri
     */
    public function mandiri(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'Mandiri',
            'nama_ponpes' => 'Ponpes Mandiri ' . $this->faker->city,
        ]);
    }
}
