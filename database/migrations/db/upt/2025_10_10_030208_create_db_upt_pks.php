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
        Schema::create('db_upt_pks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('uploaded_pdf_1')->nullable();
            $table->string('uploaded_pdf_2')->nullable();

            $table->foreignId('data_upt_id')->nullable()->constrained('data_upt')->onDelete('cascade'); 

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('db_upt_pks');
    }
};
