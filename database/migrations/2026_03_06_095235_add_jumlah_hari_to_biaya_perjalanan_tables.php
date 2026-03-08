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
        Schema::table('biaya_perjalanans', function (Blueprint $table) {
            $table->integer('jumlah_hari')->default(1)->after('kategori');
        });
        Schema::table('biaya_perjalanan_temps', function (Blueprint $table) {
            $table->integer('jumlah_hari')->default(1)->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biaya_perjalanan_tables', function (Blueprint $table) {
            //
        });
    }
};
