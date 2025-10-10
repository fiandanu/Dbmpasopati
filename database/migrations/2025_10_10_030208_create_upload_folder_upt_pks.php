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
        Schema::create('upload_folder_upt_pks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upt_id');
            $table->string('uploaded_pdf')->nullable();
            $table->timestamps();

            $table->foreign('upt_id')->references('id')->on('data_upt')->onDelete('cascade');
            $table->unique('upt_id'); // Satu UPT hanya bisa punya satu record PKS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_folder_upt_pks');
    }
};
