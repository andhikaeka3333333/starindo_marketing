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
        Schema::create('tols', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->enum('tipe', ['topup', 'out']); // topup = isi, out = pemakaian
            $table->decimal('nominal', 15, 2);
            $table->decimal('saldo_akhir', 15, 2); // Saldo saat transaksi itu selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tols');
    }
};
