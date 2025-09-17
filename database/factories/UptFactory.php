<?php

namespace Database\Factories;

use App\Models\user\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;

class UptFactory extends Factory
{
    protected $model = Upt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kanwilOptions = [
            'Kanwil Banten',
            'Kanwil DKI Jakarta',
            'Kanwil Jawa Barat',
            'Kanwil Jawa Tengah',
            'Kanwil Jawa Timur',
            'Kanwil Kalimantan',
            'Kanwil Sulawesi',
            'Kanwil Sumatera'
        ];

        $tipeOptions = [
            'reguler',
            'vpas',
        ];

        return [
            'namaupt' => 'UPT ' . $this->faker->company . ' ' . $this->faker->city,
            'kanwil' => $this->faker->randomElement($kanwilOptions),
            'tipe' => $this->faker->randomElement($tipeOptions),
            'tanggal' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * State untuk UPT tipe Rutan
     */
    public function rutan(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'Rutan',
            'namaupt' => 'Rutan ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk UPT tipe Lapas
     */
    public function lapas(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'Lapas',
            'namaupt' => 'Lapas ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk UPT tipe Bapas
     */
    public function bapas(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'Bapas',
            'namaupt' => 'Bapas ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk UPT tipe LPKA
     */
    public function lpka(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'LPKA',
            'namaupt' => 'LPKA ' . $this->faker->city,
        ]);
    }

    /**
     * State untuk tanggal terbaru
     */
    public function recent(): static
    {
        return $this->state(fn(array $attributes) => [
            'tanggal' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}
