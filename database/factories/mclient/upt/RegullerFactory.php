<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\Reguller;
use App\Models\user\upt\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mclient\Reguller>
 */

class RegullerFactory extends Factory
{
    protected $model = Reguller::class;

    private static $jenisKendalaList = [
        'Sistem REGULLER tidak dapat diakses',
        'Error saat proses validasi dokumen',
        'Timeout pada sistem database',
        'Koneksi jaringan tidak stabil',
        'Server maintenance tidak terjadwal',
        'Bug pada modul laporan',
        'Masalah integrasi dengan sistem eksternal',
        'Error pada proses backup data',
        'Sistem hang saat input data besar',
        'Masalah pada user authentication',
        'Slow response time sistem',
        'Database corruption',
    ];

    private static $urgentKendalaList = [
        'Sistem REGULLER down total',
        'Database server crash',
        'Critical security breach',
        'Network infrastructure failure',
        'Data corruption terdeteksi',
    ];

    public function definition(): array
    {
        // Gunakan UptFactory untuk membuat UPT baru, atau ambil yang sudah ada
        $upt = Upt::inRandomOrder()->first() ?? Upt::factory()->create();

        $tanggalTerlapor = $this->faker->dateTimeBetween('-6 months', 'now');
        $status = $this->faker->randomElement(['selesai', 'proses', 'pending', 'terjadwal']);

        $tanggalSelesai = null;
        $durasiHari = null;

        if ($status === 'selesai') {
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays($tanggalSelesai);
        }

        return [
            'data_upt_id'      => $upt->id,
            'jenis_kendala'    => $this->faker->randomElement(self::$jenisKendalaList),
            'detail_kendala'   => $this->faker->paragraph(2),
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai'  => $tanggalSelesai,
            'durasi_hari'      => $durasiHari,
            'status'           => $status,
            'pic_1'            => $this->faker->name(),
            'pic_2'            => $this->faker->optional(0.7)->name(),
        ];
    }

    /**
     * State untuk status selesai dengan tanggal selesai
     */
    public function resolved(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalSelesai = $this->faker->dateTimeBetween($attributes['tanggal_terlapor'], 'now');
            $durasiHari = Carbon::parse($attributes['tanggal_terlapor'])->diffInDays($tanggalSelesai);

            return [
                'status' => 'selesai',
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk status dalam proses
     */
    public function inProgress(): static
    {
        return $this->state([
            'status' => 'proses',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk status pending
     */
    public function pending(): static
    {
        return $this->state([
            'status' => 'pending',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk status terjadwal
     */
    public function scheduled(): static
    {
        return $this->state([
            'status' => 'terjadwal',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk kendala urgent
     */
    public function urgent(): static
    {
        return $this->state(fn() => [
            'jenis_kendala' => $this->faker->randomElement(self::$urgentKendalaList),
            'detail_kendala' => 'URGENT: ' . $this->faker->paragraph(1),
        ]);
    }

    /**
     * State untuk data bulan ini
     */
    public function thisMonth(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of this month', 'now');
            $tanggalSelesai = null;
            $durasiHari = null;

            if ($attributes['status'] === 'selesai') {
                $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
                $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays($tanggalSelesai);
            }

            return [
                'tanggal_terlapor' => $tanggalTerlapor,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk data tahun ini
     */
    public function thisYear(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of January this year', 'now');
            $tanggalSelesai = null;
            $durasiHari = null;

            if ($attributes['status'] === 'selesai') {
                $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
                $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays($tanggalSelesai);
            }

            return [
                'tanggal_terlapor' => $tanggalTerlapor,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk UPT tertentu
     */
    public function forUpt(Upt $upt): static
    {
        return $this->state(fn (array $attributes) => [
            'data_upt_id' => $upt->id,
        ]);
    }
}