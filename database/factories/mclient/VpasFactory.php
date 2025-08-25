<?php

namespace Database\Factories\mclient;

use App\Models\mclient\Vpas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mclient\Vpas>
 */
class VpasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vpas::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar lokasi UPT yang realistis
        $lokasiUpt = [
            'Lapas Kelas I Jakarta',
            'Lapas Kelas II Jakarta',
            'Rutan Kelas I Bekasi',
            'Rutan Kelas IIB Bekasi',
            'Lapas Perempuan Kelas IIA Jakarta',
            'Lapas Perempuan Kelas IIA Tangerang',
            'Rutan Kelas I Depok',
            'Rutan Kelas IIB Bogor',
            'Lapas Kelas IIA Bogor',
            'Rutan Kelas I Bandung',
            'Lapas Kelas I Bandung',
            'Rutan Kelas IIA Cirebon',
            'Lapas Kelas IIB Garut',
            'Rutan Kelas I Sukabumi',
            'Lapas Kelas I Surabaya',
            'Rutan Kelas IIA Malang',
            'Lapas Perempuan Kelas IIA Surabaya',
            'Rutan Kelas IIB Sidoarjo',
            'Lapas Kelas I Semarang',
            'Rutan Kelas I Yogyakarta',
            'Lapas Kelas IIA Solo',
            'Rutan Kelas IIB Klaten',
            'Lapas Kelas I Medan',
            'Rutan Kelas IIA Pekanbaru',
            'Lapas Perempuan Kelas IIA Medan',
            'Rutan Kelas IIB Palembang',
            'Lapas Kelas I Makassar',
            'Rutan Kelas I Manado',
            'Lapas Kelas IIA Denpasar',
            'Rutan Kelas IIB Mataram'
        ];

        $jenis_kendala = [
            'Tidak ada sinyal',
            'Suara tidak jelas',
            'Aplikasi error',
            'Layar rusak',
            'Internet lambat',
            'Tidak bisa login',
            'Kamera bermasalah',
            'Data tidak sinkron',
            'Server down',
            'Update gagal',
            'Mikrofon rusak',
            'VPN terputus',
            'Memory penuh',
            'Android tidak support',
            'Jaringan bermasalah',
            'Aplikasi hang',
            'Video tidak jalan',
            'Koneksi timeout',
            'Database error',
            'Firewall block',
            'Maintenance rutin',
            'Aplikasi lambat',
            'SSL expired',
            'Recording error',
            'Notifikasi tidak masuk'
        ];

        $detail_kendala = [
            'Sinyal internet lemah menyebabkan lag dan putus panggilan',
            'Audio mengalami distorsi dan feedback yang mengganggu',
            'Aplikasi force close berkali-kali dalam sehari',
            'Layar tablet retak dan touch screen tidak responsif',
            'Kecepatan internet di bawah 2 Mbps tidak mendukung HD',
            'Username dan password tidak dapat diverifikasi sistem',
            'Hasil foto buram dan pencahayaan tidak optimal',
            'Data tidak terupdate realtime dengan server pusat',
            'Server maintenance tidak terjadwal selama 3 jam',
            'Proses update terhenti di 50% dan rollback otomatis',
            'Hardware microphone rusak atau driver bermasalah',
            'Koneksi VPN drop setiap 10-15 menit sekali',
            'Memory internal tersisa kurang dari 500MB',
            'Aplikasi tidak kompatibel dengan Android 14 ke atas',
            'Provider Telkomsel mengalami gangguan area Jakarta',
            'RAM tidak mencukupi menyebabkan aplikasi freeze',
            'Format H.264 tidak didukung oleh perangkat lama',
            'Panggilan terputus otomatis setelah 30 detik',
            'Koneksi database melebihi batas waktu 10 detik',
            'Port aplikasi diblokir oleh security system kantor',
            'Maintenance berkala setiap hari Minggu pukul 02:00',
            'Response time lambat saat jam kerja 08:00-17:00',
            'Sertifikat keamanan kadaluarsa dan perlu renewal',
            'Fitur perekaman tidak dapat menyimpan file audio',
            'Notifikasi panggilan masuk tidak muncul di layar lock'
        ];

        // Daftar nama PIC yang realistis
        $namaPic = [
            'Ahmad Ridwan',
            'Siti Nurhaliza',
            'Budi Santoso',
            'Maya Sari',
            'Dian Pratiwi',
            'Eko Wijaya',
            'Fatimah Zahra',
            'Agus Setiawan',
            'Hendra Gunawan',
            'Lisa Andriani',
            'Indra Kusuma',
            'Nita Puspitasari',
            'Joko Widodo',
            'Ratna Dewi',
            'Kartika Sari',
            'Lukman Hakim',
            'Muhammad Ali',
            'Novi Rahayu',
            'Oscar Lawalata',
            'Putri Handayani',
            'Rudi Hermawan',
            'Sari Indah',
            'Tono Sugiarto',
            'Uci Sanusi',
            'Vina Amelia',
            'Wawan Setiawan',
            'Yanti Kusuma',
            'Zaky Rahman',
            'Ani Suryani',
            'Bayu Pratama',
            'Citra Dewi',
            'Dedi Kurniawan',
            'Ela Fitriani',
            'Fandi Ahmad',
            'Gita Puspita',
            'Hadi Wijaya'
        ];

        // Status yang tersedia
        $statusList = ['pending', 'proses', 'selesai'];

        // Generate tanggal terlapor dalam 3 bulan terakhir
        $tanggalTerlapor = $this->faker->dateTimeBetween('-3 months', 'now');

        // Tentukan status secara random
        $status = $this->faker->randomElement($statusList);

        // Generate tanggal selesai dan durasi berdasarkan status
        $tanggalSelesai = null;
        $durasiHari = null;

        if ($status === 'selesai') {
            // Jika selesai, generate tanggal selesai 1-30 hari setelah tanggal terlapor
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, '+30 days');
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
        } elseif ($status === 'proses' && $this->faker->boolean(30)) {
            // 30% kemungkinan untuk status proses memiliki estimasi tanggal selesai
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, '+15 days');
            $durasiHari = Carbon::parse($tanggalTerlapor)->diffInDays(Carbon::parse($tanggalSelesai));
        }

        return [
            'lokasi' => $this->faker->randomElement($lokasiUpt),
            'jenis_kendala' => $this->faker->randomElement($jenis_kendala),
            'detail_kendala' => $this->faker->randomElement($detail_kendala),
            'tanggal_terlapor' => $tanggalTerlapor->format('Y-m-d'),
            'tanggal_selesai' => $tanggalSelesai ? $tanggalSelesai->format('Y-m-d') : null,
            'durasi_hari' => $durasiHari ? (string)$durasiHari : null,
            'status' => $status,
            'pic_1' => $this->faker->randomElement($namaPic),
            'pic_2' => $this->faker->randomElement($namaPic),
        ];
    }

    /**
     * Indicate that the monitoring client has pending status.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * Indicate that the monitoring client is in progress.
     */
    public function proses(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'proses',
            'tanggal_selesai' => null,
            'durasi_hari' => null,
        ]);
    }

    /**
     * Indicate that the monitoring client is completed.
     */
    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = Carbon::parse($attributes['tanggal_terlapor']);
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, '+30 days');
            $durasi = $tanggalTerlapor->diffInDays($tanggalSelesai);

            return [
                'status' => 'selesai',
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'durasi_hari' => (string)$durasi,
            ];
        });
    }

    /**
     * Create with specific location.
     */
    public function withLocation(string $lokasi): static
    {
        return $this->state(fn(array $attributes) => [
            'lokasi' => $lokasi,
        ]);
    }

    /**
     * Create with specific kendala.
     */
    public function withKendala(string $kendala): static
    {
        return $this->state(fn(array $attributes) => [
            'kendala_Vpas' => $kendala,
        ]);
    }

    /**
     * Create with specific PICs.
     */
    public function withPics(string $pic1, string $pic2 = null): static
    {
        return $this->state(fn(array $attributes) => [
            'pic_1' => $pic1,
            'pic_2' => $pic2 ?? $this->faker->randomElement([
                'Ahmad Ridwan',
                'Siti Nurhaliza',
                'Budi Santoso',
                'Maya Sari'
            ]),
        ]);
    }

    /**
     * Create recent data (within last month).
     */
    public function recent(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('-1 month', 'now');

            return [
                'tanggal_terlapor' => $tanggalTerlapor->format('Y-m-d'),
            ];
        });
    }

    /**
     * Create old data (more than 3 months ago).
     */
    public function old(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = $this->faker->dateTimeBetween('-1 year', '-3 months');

            return [
                'tanggal_terlapor' => $tanggalTerlapor->format('Y-m-d'),
            ];
        });
    }

    /**
     * Create with long duration (more than 15 days).
     */
    public function longDuration(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = Carbon::parse($attributes['tanggal_terlapor']);
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor->addDays(15), $tanggalTerlapor->addDays(60));
            $durasi = Carbon::parse($attributes['tanggal_terlapor'])->diffInDays($tanggalSelesai);

            return [
                'status' => 'selesai',
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'durasi_hari' => (string)$durasi,
            ];
        });
    }

    /**
     * Create with short duration (less than 3 days).
     */
    public function shortDuration(): static
    {
        return $this->state(function (array $attributes) {
            $tanggalTerlapor = Carbon::parse($attributes['tanggal_terlapor']);
            $tanggalSelesai = $this->faker->dateTimeBetween($tanggalTerlapor, $tanggalTerlapor->addDays(3));
            $durasi = Carbon::parse($attributes['tanggal_terlapor'])->diffInDays($tanggalSelesai);

            return [
                'status' => 'selesai',
                'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                'durasi_hari' => (string)$durasi,
            ];
        });
    }
}
