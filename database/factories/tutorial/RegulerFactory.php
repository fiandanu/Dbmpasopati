<?php

namespace Database\Factories\tutorial;

use App\Models\tutorial\upt\Reguller;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RegulerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reguller::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tutor_reguller' => $this->faker->unique()->bothify('TUTOR-VPA-####-????'), // e.g., TUTOR-VPA-1234-ABCD
            'tanggal' => $this->faker->date(),
            'uploaded_pdf' => $this->faker->boolean(70) ? $this->faker->filePath() . '.pdf' : null,
            'pdf_folder_1' => $this->faker->boolean(60) ? 'folder/path/' . $this->faker->word . '_1' : null,
            'pdf_folder_2' => $this->faker->boolean(50) ? 'folder/path/' . $this->faker->word . '_2' : null,
            'pdf_folder_3' => $this->faker->boolean(40) ? 'folder/path/' . $this->faker->word . '_3' : null,
            'pdf_folder_4' => $this->faker->boolean(30) ? 'folder/path/' . $this->faker->word . '_4' : null,
            'pdf_folder_5' => $this->faker->boolean(25) ? 'folder/path/' . $this->faker->word . '_5' : null,
            'pdf_folder_6' => $this->faker->boolean(20) ? 'folder/path/' . $this->faker->word . '_6' : null,
            'pdf_folder_7' => $this->faker->boolean(15) ? 'folder/path/' . $this->faker->word . '_7' : null,
            'pdf_folder_8' => $this->faker->boolean(10) ? 'folder/path/' . $this->faker->word . '_8' : null,
            'pdf_folder_9' => $this->faker->boolean(5) ? 'folder/path/' . $this->faker->word . '_9' : null,
            'pdf_folder_10' => $this->faker->boolean(5) ? 'folder/path/' . $this->faker->word . '_10' : null,
        ];
    }

    /**
     * State untuk data dengan semua folder terisi
     */
    public function withAllFolders(): static
    {
        return $this->state(function (array $attributes) {
            $folders = [];
            for ($i = 1; $i <= 10; $i++) {
                $folders["pdf_folder_$i"] = 'folder/path/' . $this->faker->word . "_$i";
            }
            return $folders;
        });
    }

    /**
     * State untuk data tanpa folder PDF
     */
    public function withoutFolders(): static
    {
        return $this->state(function (array $attributes) {
            $folders = [];
            for ($i = 1; $i <= 10; $i++) {
                $folders["pdf_folder_$i"] = null;
            }
            return $folders;
        });
    }

    /**
     * State untuk data dengan uploaded PDF
     */
    public function withUploadedPdf(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'uploaded_pdf' => 'uploads/pdfs/' . $this->faker->uuid . '.pdf',
            ];
        });
    }

    /**
     * State untuk data tanpa uploaded PDF
     */
    public function withoutUploadedPdf(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'uploaded_pdf' => null,
            ];
        });
    }
}
