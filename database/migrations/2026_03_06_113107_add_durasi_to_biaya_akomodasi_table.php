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
        Schema::table('biaya_akomodasi', function (Blueprint $table) {
            $table->integer('durasi')->default(1);
        });

        Schema::table('temp_akomodasi', function (Blueprint $table) {
            $table->integer('durasi')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biaya_akomodasi', function (Blueprint $table) {
            //
        });
    }
};
