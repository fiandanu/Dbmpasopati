<?php

namespace Database\Factories;

use App\Models\db\ponpes\DataOpsionalPonpes;
use App\Models\user\namaWilayah\NamaWilayah;
use App\Models\user\ponpes\Ponpes;
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
        $wilayahOptions = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Jambi',
            'Sumatera Selatan',
            'Bengkulu',
            'Lampung',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Banten',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua',
            'Papua Barat'
        ];

        $tipeOptions = ['reguler', 'vtren'];

        $ponpesPrefixes = [
            'Pondok Pesantren',
            'Pesantren',
            'PP',
            'Ponpes'
        ];

        $ponpesNames = [
            'Al-Hikmah',
            'Darul Ulum',
            'Al-Falah',
            'Nurul Huda',
            'Al-Munawwir',
            'Darussalam',
            'Al-Ikhlas',
            'Hidayatullah',
            'Al-Muhajirin',
            'Raudlatul Ulum',
            'Al-Ishlah',
            'Baitul Hikmah',
            'Al-Kautsar',
            'Nurul Islam',
            'Al-Furqan',
            'Darul Muttaqien',
            'Al-Ittihad',
            'Miftahul Huda',
            'Al-Barokah',
            'Riyadhus Sholihin'
        ];

        return [
            'nama_ponpes' => $this->faker->randomElement($ponpesPrefixes) . ' ' .
                $this->faker->randomElement($ponpesNames) . ' ' .
                $this->faker->city,
            'nama_wilayah_id' => NamaWilayah::all()->random()->id, // sesuai jumlah data di tabel nama_wilayah
            'tipe' => $this->faker->randomElement($tipeOptions),
            'tanggal' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    /**
     * State untuk ponpes tipe reguler
     */
    public function reguler(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'reguler',
        ]);
    }

    /**
     * State untuk ponpes tipe vtren
     */
    public function vtren(): static
    {
        return $this->state(fn(array $attributes) => [
            'tipe' => 'vtren',
        ]);
    }

    /**
     * State untuk ponpes di Jawa
     */
    public function jawa(): static
    {
        $wilayahJawa = [
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Banten'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_wilayah_id' => $this->faker->randomElement($wilayahJawa),
        ]);
    }

    /**
     * State untuk ponpes di Sumatera
     */
    public function sumatera(): static
    {
        $wilayahSumatera = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Jambi',
            'Sumatera Selatan',
            'Bengkulu',
            'Lampung'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_wilayah_id' => $this->faker->randomElement($wilayahSumatera),
        ]);
    }

    /**
     * State untuk ponpes di Kalimantan
     */
    public function kalimantan(): static
    {
        $wilayahKalimantan = [
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_wilayah_id' => $this->faker->randomElement($wilayahKalimantan),
        ]);
    }

    /**
     * State untuk ponpes di Sulawesi
     */
    public function sulawesi(): static
    {
        $wilayahSulawesi = [
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat'
        ];

        return $this->state(fn(array $attributes) => [
            'nama_wilayah_id' => $this->faker->randomElement($wilayahSulawesi),
        ]);
    }

    /**
     * State untuk ponpes dengan nama khusus berdasarkan wilayah
     */
    public function withRegionalName(): static
    {
        return $this->state(function (array $attributes) {
            $wilayah = $attributes['nama_wilayah_id'] ?? $this->faker->randomElement([
                'Aceh',
                'Jawa Barat',
                'Jawa Timur',
                'Sulawesi Selatan'
            ]);

            $regionalNames = [
                'Aceh' => ['Dayah', 'Babun', 'Jeumala'],
                'Jawa Barat' => ['Daarut Tauhiid', 'Al-Zaytun', 'Persatuan Islam'],
                'Jawa Timur' => ['Tebuireng', 'Lirboyo', 'Ploso'],
                'Jawa Tengah' => ['Roudlotul Mubtadiin', 'Al-Hikmah 2'],
                'Sulawesi Selatan' => ['DDI', 'As\'adiyah', 'Darul Dakwah']
            ];

            $names = $regionalNames[$wilayah] ?? ['Al-Hikmah', 'Darul Ulum', 'Al-Falah'];

            return [
                'nama_ponpes' => 'Pondok Pesantren ' . $this->faker->randomElement($names) . ' ' .
                    $this->faker->city,
                'nama_wilayah_id' => $wilayah,
            ];
        });
    }

    /**
     * State untuk tanggal terbaru
     */
    public function recent(): static
    {
        return $this->state(fn(array $attributes) => [
            'tanggal' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    /**
     * State untuk tanggal lama
     */
    public function old(): static
    {
        return $this->state(fn(array $attributes) => [
            'tanggal' => $this->faker->dateTimeBetween('-5 years', '-1 year'),
        ]);
    }

    public function dataOpsional()
    {
        return $this->hasOne(DataOpsionalPonpes::class, 'ponpes_id');
    }
}
