<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mclient_catatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_upt')->nullable();
            $table->string('spam_vpas_kartu_baru')->nullable();
            $table->string('spam_vpas_kartu_bekas')->nullable();
            $table->string('spam_vpas_kartu_goip')->nullable();
            $table->string('kartu_belum_teregister')->nullable();
            $table->string('whatsapp_telah_terpakai')->nullable();
            $table->string('card_supporting')->nullable();
            $table->string('pic')->nullable();
            $table->string('jumlah_kartu_terpakai_perhari')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mclient_catatan');
    }
};