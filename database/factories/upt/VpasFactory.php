<?php

namespace Database\Factories\upt;

use App\Models\tutorial\upt\Vpas;
use Illuminate\Database\Eloquent\Factories\Factory;

class VpasFactory extends Factory
{

    protected $model = Vpas::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tutor_vpas' => $this->faker->company(),
            'tanggal' => $this->faker->date(), // karena di migration string, tapi bisa dipake date string
            'uploaded_pdf' => null, // default null, bisa nanti isi manual kalau upload file
        ];
    }
}
