<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mclient_ponpes_pengiriman', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ponpes')->nullable();
            $table->string('jenis_layanan')->nullable(); // vtren, reguler, vtrenreg
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

    public function down(): void
    {
        Schema::dropIfExists('mclient_ponpes_pengiriman');
    }
};