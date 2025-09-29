<?php

namespace Database\Factories\Mclient\Ponpes;

use App\Models\mclient\ponpes\Reguller;
use App\Models\user\Ponpes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class RegullerFactory extends Factory
{
    protected $model = Reguller::class;

    public function definition(): array
    {
        $tanggalTerlapor = $this->faker->dateTimeBetween('-6 months', 'now');
        $tanggalSelesai = null;
        $status = $this->faker->randomElement(['selesai', 'proses', 'pending', 'terjadwal']);
        
        if ($status === 'selesai') {
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, 'now');
        }

        $durasiHari = null;
        if ($tanggalSelesai) {
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
        }

        return [
            'nama_ponpes' => $this->faker->randomElement([
                'PONPES Pelabuhan Tanjung Priok',
                'PONPES Pelabuhan Surabaya',
                'PONPES Pelabuhan Makassar',
                'PONPES Pelabuhan Medan',
                'PONPES Pelabuhan Batam',
                'PONPES Pelabuhan Semarang',
                'PONPES Pelabuhan Pontianak',
                'PONPES Pelabuhan Banjarmasin',
                'PONPES Pelabuhan Palembang',
                'PONPES Pelabuhan Lampung',
                'PONPES Pelabuhan Padang',
                'PONPES Pelabuhan Pekanbaru',
                'PONPES Pelabuhan Jambi',
                'PONPES Pelabuhan Bengkulu',
                'PONPES Pelabuhan Balikpapan'
            ]),
            'nama_wilayah' => $this->faker->randomElement([
                'I Jakarta',
                'II Surabaya',
                'III Medan',
                'IV Makassar',
                'V Batam',
                'VI Semarang',
                'VII Pontianak',
                'VIII Banjarmasin'
            ]),
            'jenis_kendala' => $this->faker->randomElement([
                'Sistem Reguller tidak dapat diakses',
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
            'pic_2' => $this->faker->optional(0.7)->name(),
        ];
    }

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

    public function urgent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'jenis_kendala' => $this->faker->randomElement([
                    'Sistem Reguller down total',
                    'Database server crash',
                    'Critical security breach',
                    'Network infrastructure failure',
                    'Data corruption terdeteksi'
                ]),
                'detail_kendala' => 'URGENT: ' . $this->faker->paragraph(1),
            ];
        });
    }

    public function thisMonth(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of this month', 'now');
            
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

    public function thisYear(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('first day of January this year', 'now');
            
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