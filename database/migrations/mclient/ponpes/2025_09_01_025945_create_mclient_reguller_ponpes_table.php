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
        Schema::create('mclient_ponpes_reguller', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendala')->nullable();
            $table->string('detail_kendala')->nullable();
            $table->string('tanggal_terlapor')->nullable();
            $table->string('tanggal_selesai')->nullable();
            $table->string('durasi_hari')->nullable();
            $table->string('status')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();

            $table->foreignId('data_ponpes_id')->nullable()->constrained('data_ponpes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mclient_ponpes_reguller');
    }
};