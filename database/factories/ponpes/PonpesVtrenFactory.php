<?php

namespace Database\Factories\ponpes;

use App\Models\tutorial\ponpes\Vtren;
use Illuminate\Database\Eloquent\Factories\Factory;

class PonpesVtrenFactory extends Factory
{

    protected $model = Vtren::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tutor_ponpes_vtren' => $this->faker->company(),
            'tanggal' => $this->faker->date(), // karena di migration string, tapi bisa dipake date string
            'uploaded_pdf' => null, // default null, bisa nanti isi manual kalau upload file
        ];
    }
}
