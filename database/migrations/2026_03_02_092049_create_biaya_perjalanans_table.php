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
        Schema::create('biaya_perjalanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('kategori', [
                'Hotel',
                'UM',
                'Oleh-oleh',
                'Cuci Kendaraan',
                'Parkir',
                'Tambah Angin',
                'Lain-lain'
            ]);
            $table->integer('level')->nullable();
            $table->string('wilayah')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_perjalanans');
    }
};
