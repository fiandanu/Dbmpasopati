<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mclient_vpas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upt')->nullable();
            $table->string('kanwil')->nullable();
            $table->string('jenis_kendala')->nullable();
            $table->string('detail_kendala')->nullable();
            $table->string('tanggal_terlapor')->nullable();
            $table->string('tanggal_selesai')->nullable();
            $table->string('durasi_hari')->nullable();
            $table->string('status')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mclient_vpas');
    }
};