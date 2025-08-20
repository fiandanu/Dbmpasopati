<?php

namespace Database\Factories\upt;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\tutorial\upt\Mikrotik;

class MikrotikFactory extends Factory
{
    protected $model = Mikrotik::class;

    public function definition(): array
    {
        return [
            'tutor_mikrotik' => $this->faker->company(),
            'tanggal' => $this->faker->date(), // karena di migration string, tapi bisa dipake date string
            'uploaded_pdf' => null, // default null, bisa nanti isi manual kalau upload file
        ];
    }
}
