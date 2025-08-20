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
        Schema::create('tutor_ponpes_vtren', function (Blueprint $table) {
            $table->id();
            $table->string('tutor_ponpes_vtren')->unique();
            $table->string('tanggal')->default(now());
            $table->string('uploaded_pdf')->nullable();
            for ($i = 1; $i <= 10; $i++) {
                $table->string('pdf_folder_' . $i)->nullable();
            }
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_ponpes_vtren');
    }
};
