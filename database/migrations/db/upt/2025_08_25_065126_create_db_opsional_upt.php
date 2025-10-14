<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 
    public function up(): void
    {
        Schema::create('db_opsional_upt', function (Blueprint $table) {
            $table->id();
            $table->string('pic_upt')->nullable();
            $table->string('no_telpon')->nullable();
            $table->string('alamat')->nullable();
            $table->integer('jumlah_wbp')->nullable();
            $table->integer('jumlah_line')->nullable();
            $table->string('provider_internet')->nullable();
            $table->string('kecepatan_internet')->nullable();
            $table->decimal('tarif_wartel', total: 10, places: 2)->nullable();
            $table->string('status_wartel')->nullable();
            $table->string('akses_topup_pulsa')->nullable();
            $table->string('password_topup')->nullable();
            $table->string('akses_download_rekaman')->nullable();
            $table->string('password_download')->nullable();
            $table->string('internet_protocol')->nullable();
            $table->string('vpn_user')->nullable();
            $table->string('vpn_password')->nullable();
            $table->integer('jumlah_extension')->nullable();
            $table->string('pin_tes')->nullable();
            $table->string('no_extension')->nullable();
            $table->string('extension_password')->nullable();
            $table->string('no_pemanggil')->nullable();
            $table->string('email_airdroid')->nullable();
            $table->string('password')->nullable();

            $table->foreignId('vpns_id')->nullable()->constrained('vpns')->onDelete('cascade');
            $table->foreignId('data_upt_id')->nullable()->constrained('data_upt')->onDelete('cascade');
            $table->foreignId('provider_id')->nullable()->constrained('providers')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Perbaikan: nama tabel harus sesuai dengan yang dibuat
        Schema::dropIfExists('db_opsional_upt');
    }
};
