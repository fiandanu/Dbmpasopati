<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_opsional_ponpes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ponpes_id')->constrained('data_ponpes')->onDelete('cascade');
            $table->string('pic_ponpes')->nullable();
            $table->string('no_telpon')->nullable();
            $table->text('alamat')->nullable();
            $table->integer('jumlah_wbp')->nullable();
            $table->integer('jumlah_line')->nullable();
            $table->string('provider_internet')->nullable();
            $table->string('kecepatan_internet')->nullable();
            $table->decimal('tarif_wartel', 10, 2)->nullable();
            $table->string('status_wartel')->nullable();
            $table->string('akses_topup_pulsa')->nullable();
            $table->string('password_topup')->nullable();
            $table->string('akses_download_rekaman')->nullable();
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
        Schema::dropIfExists('data_opsional_ponpes');
    }
};