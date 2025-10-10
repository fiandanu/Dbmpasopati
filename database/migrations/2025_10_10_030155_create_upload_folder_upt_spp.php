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
        Schema::create('upload_folder_upt_spp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upt_id');
            $table->string('pdf_folder_1')->nullable();
            $table->string('pdf_folder_2')->nullable();
            $table->string('pdf_folder_3')->nullable();
            $table->string('pdf_folder_4')->nullable();
            $table->string('pdf_folder_5')->nullable();
            $table->string('pdf_folder_6')->nullable();
            $table->string('pdf_folder_7')->nullable();
            $table->string('pdf_folder_8')->nullable();
            $table->string('pdf_folder_9')->nullable();
            $table->string('pdf_folder_10')->nullable();
            $table->timestamps();

            $table->foreign('upt_id')->references('id')->on('data_upt')->onDelete('cascade');
            $table->unique('upt_id'); // Satu UPT hanya bisa punya satu record SPP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_folder_upt_spp');
    }
};
