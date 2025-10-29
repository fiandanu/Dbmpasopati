<?php

namespace Database\Factories\Mclient\Upt;

use App\Models\mclient\Pengiriman;
use App\Models\user\upt\Upt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PengirimanFactory extends Factory
{
    protected $model = Pengiriman::class;

    public function definition(): array
    {
        // Gunakan UptFactory untuk membuat UPT baru, atau ambil yang sudah ada
        $upt = Upt::inRandomOrder()->first() ?? Upt::factory()->create();

        $tanggalPengiriman = $this->faker->dateTimeBetween('-3 months', 'now');
        $status = $this->faker->randomElement(['pending', 'terjadwal', 'proses', 'selesai']);

        $tanggalSampai = null;
        $durasiHari = null;

        // Jika status selesai, set tanggal sampai dan durasi
        if ($status === 'selesai') {
            $durasiHari = $this->faker->numberBetween(1, 14);
            $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");
        }

        return [
            'data_upt_id'         => $upt->id,
            'jenis_layanan'       => $this->faker->randomElement(['vpas', 'reguler', 'vpasreg']),
            'keterangan'          => $this->faker->optional(0.8)->paragraph(2),
            'tanggal_pengiriman'  => $tanggalPengiriman,
            'tanggal_sampai'      => $tanggalSampai,
            'durasi_hari'         => $durasiHari,
            'pic_1'               => $this->faker->name(),
            'pic_2'               => $this->faker->optional(0.6)->name(),
            'status'              => $status,
        ];
    }

    /**
     * State untuk status pending
     */
    public function pending(): static
    {
        return $this->state([
            'status' => 'pending',
            'tanggal_pengiriman' => null,
            'tanggal_sampai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * State untuk status terjadwal
     */
    public function terjadwal(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $this->faker->dateTimeBetween('now', '+1 month');

            return [
                'status' => 'terjadwal',
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => null,
                'durasi_hari' => null,
            ];
        });
    }

    /**
     * State untuk status proses
     */
    public function proses(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $this->faker->dateTimeBetween('-1 week', 'now');

            return [
                'status' => 'proses',
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => null,
                'durasi_hari' => null,
            ];
        });
    }

    /**
     * State untuk status selesai
     */
    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $this->faker->dateTimeBetween('-2 months', '-1 week');
            $durasiHari = $this->faker->numberBetween(1, 14);
            $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");

            return [
                'status' => 'selesai',
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => $tanggalSampai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk jenis layanan VPAS
     */
    public function vpas(): static
    {
        return $this->state([
            'jenis_layanan' => 'vpas',
        ]);
    }

    /**
     * State untuk jenis layanan Reguler
     */
    public function reguler(): static
    {
        return $this->state([
            'jenis_layanan' => 'reguler',
        ]);
    }

    /**
     * State untuk jenis layanan VPAS + Reguler
     */
    public function vpasReguler(): static
    {
        return $this->state([
            'jenis_layanan' => 'vpasreg',
        ]);
    }

    /**
     * State untuk UPT tertentu
     */
    public function forUpt(Upt $upt): static
    {
        return $this->state(fn(array $attributes) => [
            'data_upt_id' => $upt->id,
        ]);
    }

    /**
     * State untuk data bulan ini
     */
    public function thisMonth(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $this->faker->dateTimeBetween('first day of this month', 'now');
            $tanggalSampai = null;
            $durasiHari = null;

            if ($attributes['status'] === 'selesai') {
                $durasiHari = $this->faker->numberBetween(1, 14);
                $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");
            }

            return [
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => $tanggalSampai,
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
            $tanggalPengiriman = $this->faker->dateTimeBetween('first day of January this year', 'now');
            $tanggalSampai = null;
            $durasiHari = null;

            if ($attributes['status'] === 'selesai') {
                $durasiHari = $this->faker->numberBetween(1, 14);
                $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");
            }

            return [
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => $tanggalSampai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk pengiriman cepat (1-3 hari)
     */
    public function express(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $attributes['tanggal_pengiriman'] ?? $this->faker->dateTimeBetween('-1 month', 'now');
            $durasiHari = $this->faker->numberBetween(1, 3);
            $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");

            return [
                'status' => 'selesai',
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => $tanggalSampai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }

    /**
     * State untuk pengiriman lambat (7-14 hari)
     */
    public function slow(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalPengiriman = $attributes['tanggal_pengiriman'] ?? $this->faker->dateTimeBetween('-2 months', 'now');
            $durasiHari = $this->faker->numberBetween(7, 14);
            $tanggalSampai = (clone $tanggalPengiriman)->modify("+{$durasiHari} days");

            return [
                'status' => 'selesai',
                'tanggal_pengiriman' => $tanggalPengiriman,
                'tanggal_sampai' => $tanggalSampai,
                'durasi_hari' => $durasiHari,
            ];
        });
    }
}
