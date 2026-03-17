<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_biaya_tols_table.php
    public function up()
    {
        // Tabel Resmi
        Schema::create('biaya_tols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->dateTime('tanggal');
            $table->string('customer_nama')->nullable();
            $table->string('customer_cp')->nullable();
            $table->enum('kategori', ['Top-Up Tol', 'Pemakaian Tol']);
            $table->string('nama_gerbang')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        // Tabel Temp (Draf)
        Schema::create('temp_tols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->dateTime('tanggal');
            $table->string('customer_nama')->nullable();
            $table->string('customer_cp')->nullable();
            $table->enum('kategori', ['Top-Up Tol', 'Pemakaian Tol']);
            $table->string('nama_gerbang')->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_tols');
    }
};
