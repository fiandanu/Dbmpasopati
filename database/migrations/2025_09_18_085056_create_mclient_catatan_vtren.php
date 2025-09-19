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
        Schema::create('mclient_catatan_vtren', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ponpes')->nullable();
            $table->string('spam_vtren_kartu_baru')->nullable()->default('0');
            $table->string('spam_vtren_kartu_bekas')->nullable()->default('0');
            $table->string('spam_vtren_kartu_goip')->nullable()->default('0');
            $table->string('kartu_belum_teregister')->nullable()->default('0');
            $table->string('whatsapp_telah_terpakai')->nullable()->default('0');
            $table->string('card_supporting')->nullable();
            $table->string('pic')->nullable();
            $table->string('jumlah_kartu_terpakai_perhari')->nullable()->default('0');
            $table->date('tanggal')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mclient_catatan_vtren');
    }
};