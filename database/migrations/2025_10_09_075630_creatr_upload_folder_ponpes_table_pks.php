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
        Schema::create('upload_folder_ponpes_pks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ponpes_id');
            $table->date('tanggal_kontrak')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('uploaded_pdf')->nullable();
            $table->timestamps();

            $table->foreign('ponpes_id')->references('id')->on('data_ponpes')->onDelete('cascade');
            $table->unique('ponpes_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_folder_ponpes_pks');
    }
};
