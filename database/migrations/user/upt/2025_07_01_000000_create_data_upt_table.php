<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_upt', function (Blueprint $table) {
            $table->id();
            $table->string('namaupt');
            $table->string('tipe')->nullable();
            $table->date('tanggal')->nullable();

            $table->foreignId('kanwil_id')->constrained('kanwil')->onDelete('cascade'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_upt');
    }
};
