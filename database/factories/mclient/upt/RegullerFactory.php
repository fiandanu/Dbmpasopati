<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\Reguller;
use App\Models\User\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mclient\Reguller>
 */
class RegullerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reguller::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-6 months', 'now');
        $tanggalSelesai = null;
        $status = $this->faker->randomElement(['selesai', 'proses', 'pending', 'terjadwal']);
        
        // Jika status selesai, set tanggal selesai
        if ($status === 'selesai') {
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
        }

        // Hitung durasi hari jika ada tanggal selesai
        $durasiHari = null;
        if ($tanggalSelesai) {
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
        }

        return [
            'nama_upt' => $this->faker->randomElement([
                'UPT Pelabuhan Tanjung Priok',
                'UPT Pelabuhan Surabaya',
                'UPT Pelabuhan Makassar',
                'UPT Pelabuhan Medan',
                'UPT Pelabuhan Batam',
                'UPT Pelabuhan Semarang',
                'UPT Pelabuhan Pontianak',
                'UPT Pelabuhan Banjarmasin',
                'UPT Pelabuhan Palembang',
                'UPT Pelabuhan Lampung',
                'UPT Pelabuhan Padang',
                'UPT Pelabuhan Pekanbaru',
                'UPT Pelabuhan Jambi',
                'UPT Pelabuhan Bengkulu',
                'UPT Pelabuhan Balikpapan'
            ]),
            'kanwil' => $this->faker->randomElement([
                'Kanwil I Jakarta',
                'Kanwil II Surabaya',
                'Kanwil III Medan',
                'Kanwil IV Makassar',
                'Kanwil V Batam',
                'Kanwil VI Semarang',
                'Kanwil VII Pontianak',
                'Kanwil VIII Banjarmasin'
            ]),
            'jenis_kendala' => $this->faker->randomElement([
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
                'Database corruption'
            ]),
            'detail_kendala' => $this->faker->paragraph(2),
            'tanggal_terlapor' => $tanggalTerlapor,
            'tanggal_selesai' => $tanggalSelesai,
            'durasi_hari' => $durasiHari,
            'status' => $status,
            'pic_1' => $this->faker->name(),
            'pic_2' => $this->faker->optional(0.7)->name(), // 70% kemungkinan ada PIC ke-2
        ];
    }

    /**
     * Indicate that the issue is resolved.
     */
    public function resolved(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = Carbon::parse($attributes['tanggal_terlapor']);
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
            $durasiHari = $tanggalTerlapor->diffInDays(Carbon::parse($tanggalSelesai));

            return [
                'status' => 'selesai',
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * Indicate that the issue is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'proses',
                'tanggal_selesai' => null,
                'durasi_hari' => null,
            ];
        });
    }

    /**
     * Indicate that the issue is pending.
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'tanggal_selesai' => null,
                'durasi_hari' => null,
            ];
        });
    }

    /**
     * Indicate that the issue is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'terjadwal',
                'tanggal_selesai' => null,
                'durasi_hari' => null,
            ];
        });
    }

    /**
     * Indicate that the issue is urgent (high priority).
     */
    public function urgent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis_kendala' => $this->faker->randomElement([
                    'Sistem REGULLER down total',
                    'Database server crash',
                    'Critical security breach',
                    'Network infrastructure failure',
                    'Data corruption terdeteksi'
                ]),
                'detail_kendala' => 'URGENT: ' . $this->faker->paragraph(1),
            ];
        });
    }

    /**
     * Indicate that the issue occurred this month.
     */
    public function thisMonth(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of this month', 'now');
            
            // Recalculate tanggal_selesai and durasi_hari if status is resolved
            $tanggalSelesai = null;
            $durasiHari = null;
            
            if ($attributes['status'] === 'selesai') {
                $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
                $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
            }

            return [
                'tanggal_terlapor' => $tanggalTerlapor,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * Indicate that the issue occurred this year.
     */
    public function thisYear(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of January this year', 'now');
            
            // Recalculate tanggal_selesai and durasi_hari if status is resolved
            $tanggalSelesai = null;
            $durasiHari = null;
            
            if ($attributes['status'] === 'selesai') {
                $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
                $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
            }

            return [
                'tanggal_terlapor' => $tanggalTerlapor,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }
}