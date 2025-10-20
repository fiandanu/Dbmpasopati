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
        Schema::create('pengiriman_upt', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upt')->nullable();
            $table->string('jenis_layanan')->nullable(); // vpas, reguler, vpasreg
            $table->text('keterangan')->nullable();
            $table->date('tanggal_pengiriman')->nullable();
            $table->date('tanggal_sampai')->nullable();
            $table->integer('durasi_hari')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();
            $table->string('status')->nullable(); // pending, proses, selesai, terjadwal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman_upt');
    }
};
