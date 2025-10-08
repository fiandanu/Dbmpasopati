<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_folder_ponpes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ponpes_id')->constrained('data_ponpes')->onDelete('cascade');
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('uploaded_pdf')->nullable();
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_folder_ponpes');
    }
};
