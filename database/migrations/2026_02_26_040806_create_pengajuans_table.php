<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_pengajuans_table.php
    public function up()
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->enum('jenis_pengajuan', ['Komisi Penjualan', 'Entertain', 'Proposal']); //
            $table->decimal('nominal_value', 15, 2);
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
