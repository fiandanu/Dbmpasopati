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
        Schema::create('db_upt_spp', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('data_upt_id');
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

            $table->foreignId('data_upt_id')->nullable()->constrained('data_upt')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('db_upt_spp');
    }
};
