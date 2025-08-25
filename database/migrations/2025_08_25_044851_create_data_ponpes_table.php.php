<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_ponpes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ponpes');
            $table->string('nama_wilayah')->nullable();
            $table->string('tipe')->nullable();
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ponpes');
    }
};