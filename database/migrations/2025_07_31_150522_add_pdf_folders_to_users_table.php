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
        Schema::table('users', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->string('pdf_folder_' . $i)->nullable()->after('uploaded_pdf');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            for ($i = 1; $i <= 10; $i++) {
                $table->dropColumn('pdf_folder_' . $i);
            }
        });
    }
};
