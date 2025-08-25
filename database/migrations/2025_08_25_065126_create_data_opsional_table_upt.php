<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_opsional_upt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upt_id')->constrained('data_upt')->onDelete('cascade');
            $table->string('pic_upt')->nullable();
            $table->string('no_telpon')->nullable();
            $table->text('alamat')->nullable();
            $table->integer('jumlah_wbp')->nullable();
            $table->integer('jumlah_line_reguler')->nullable();
            $table->string('provider_internet')->nullable();
            $table->string('kecepatan_internet')->nullable();
            $table->decimal('tarif_wartel_reguler', 10, 2)->nullable();
            $table->boolean('status_wartel')->default(0);
            $table->boolean('akses_topup_pulsa')->default(0);
            $table->string('password_topup')->nullable();
            $table->boolean('akses_download_rekaman')->default(0);
            $table->string('password_download')->nullable();
            $table->string('internet_protocol')->nullable();
            $table->string('vpn_user')->nullable();
            $table->string('vpn_password')->nullable();
            $table->string('jenis_vpn')->nullable();
            $table->integer('jumlah_extension')->nullable();
            $table->string('pin_tes')->nullable();
            $table->string('no_extension')->nullable();
            $table->string('extension_password')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Perbaikan: nama tabel harus sesuai dengan yang dibuat
        Schema::dropIfExists('data_opsional_upt');
    }
};