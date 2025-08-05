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
        Schema::create('ponpes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ponpes')->unique();
            $table->string('nama_wilayah');
            $table->string('tanggal')->default(now());
            $table->string('uploaded_pdf')->nullable();
            $table->string('tipe')->nullable();

            // Data Opsional (Form VPAS)
            $table->string('pic_ponpes')->nullable();
            $table->string('no_telpon',)->nullable();
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
            $table->integer('pin_tes')->nullable();
            $table->text('no_extension')->nullable();
            $table->text('extension_password')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponpes');
    }
};
