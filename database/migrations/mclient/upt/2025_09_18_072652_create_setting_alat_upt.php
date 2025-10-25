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
        Schema::create('setting_alat_upt', function (Blueprint $table) {
            $table->id();
            // $table->string('nama_upt')->nullable();
            $table->string('jenis_layanan')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal_terlapor')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('durasi_hari')->nullable();
            $table->string('pic_1')->nullable();
            $table->string('pic_2')->nullable();
            $table->string('status')->nullable();

            $table->foreignId('data_upt_id')->nullable()->constrained('data_upt')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_alat_upt');
    }
};
