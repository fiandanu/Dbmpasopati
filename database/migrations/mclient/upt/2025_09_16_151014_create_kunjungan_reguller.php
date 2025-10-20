<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mclient_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upt')->nullable();
            $table->string('jenis_layanan')->nullable(); // vpas, reguler, vpasreg
            $table->text('keterangan')->nullable();
            $table->date('jadwal')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('durasi_hari')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();
            $table->string('status')->nullable(); // pending, proses, selesai, terjadwal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mclient_kunjungan');
    }
};