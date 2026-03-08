<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_biaya_bensins_table.php
    public function up()
    {
        // Tabel Resmi
        Schema::create('biaya_bensins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama')->nullable();
            $table->string('customer_cp')->nullable();
            $table->string('kategori')->default('Bensin');
            $table->integer('km')->nullable(); // Ganti km_liter jadi km
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        // Tabel Temp (Draf)
        Schema::create('temp_bensins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama')->nullable();
            $table->string('customer_cp')->nullable();
            $table->string('kategori')->default('Bensin');
            $table->integer('km')->nullable(); 
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
        Schema::dropIfExists('biaya_bensins');
    }
};
