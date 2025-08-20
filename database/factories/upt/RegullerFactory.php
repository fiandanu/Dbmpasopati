<?php

namespace Database\Factories\upt;

use App\Models\tutorial\upt\Reguller;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegullerFactory extends Factory
{
    protected $model = Reguller::class;

    public function definition(): array
    {
        return [
            'tutor_reguller' => $this->faker->company(),
            'tanggal' => $this->faker->date(), // karena di migration string, tapi bisa dipake date string
            'uploaded_pdf' => null, // default null, bisa nanti isi manual kalau upload file
        ];
    }
}