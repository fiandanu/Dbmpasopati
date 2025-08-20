<?php

namespace Database\Factories\upt;

use App\Models\tutorial\upt\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{

    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'tutor_server' => $this->faker->company(),
            'tanggal' => $this->faker->date(), // karena di migration string, tapi bisa dipake date string
            'uploaded_pdf' => null, // default null, bisa nanti isi manual kalau upload file
        ];
    }
}
