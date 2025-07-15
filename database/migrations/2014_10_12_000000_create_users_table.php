<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Field Wajib Form UPT
            $table->string('namaupt')->unique();
            $table->string('kanwil');
            $table->string('tanggal')->default(now());

            // Data Opsional (Form VPAS)
            $table->string('pic_upt')->nullable();
            $table->integer('no_telpon', )->nullable();
            $table->text('alamat')->nullable();
            // kanwil sudah ada di atas
            $table->integer('jumlah_wbp')->nullable();
            $table->integer('jumlah_line_reguler')->nullable();
            $table->string('provider_internet')->nullable();
            $table->string('kecepatan_internet')->nullable(); // bisa "50 mbps" 
            $table->integer('tarif_wartel_reguler')->nullable();
            $table->string('status_wartel')->nullable();
            // IMC PAS
            $table->string('akses_topup_pulsa')->nullable();
            $table->string('password_topup')->nullable(); // akan di-hash
            $table->text('akses_download_rekaman')->nullable();
            $table->string('password_download')->nullable(); // akan di-hash

            // AKSES VPN
            $table->string('internet_protocol')->nullable(); // IP Address
            $table->string('vpn_user')->nullable();
            $table->string('vpn_password')->nullable(); // akan di-hash
            $table->string('jenis_vpn')->nullable();

            // Extension Reguler
            $table->integer('jumlah_extension')->nullable();
            $table->string('no_extension')->nullable(); // dropdown value
            $table->string('extension_password')->nullable(); // dropdown value
            $table->integer('pin_tes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
