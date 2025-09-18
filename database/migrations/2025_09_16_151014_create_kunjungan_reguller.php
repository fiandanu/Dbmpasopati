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
        Schema::create('kunjungan_reguller', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upt')->nullable();
            $table->string('kanwil')->nullable();
            $table->string('jenis_layanan')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('jadwal')->nullable();
            $table->string('tanggal_selesai')->nullable();
            $table->string('durasi_hari')->nullable();
            $table->string('status')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_reguller');
    }
};
