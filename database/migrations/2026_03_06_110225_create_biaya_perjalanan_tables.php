<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // 1. Tabel Utama Akomodasi (Hotel & UM)
        Schema::create('biaya_akomodasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->string('kategori'); // Hotel / UM
            $table->integer('level');
            $table->string('wilayah');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        // 2. Tabel Utama Operasional (Parkir, Cuci, dll)
        Schema::create('biaya_operasional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->string('kategori');
            $table->string('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        // 3. Tabel Temp Akomodasi
        Schema::create('temp_akomodasi', function (Blueprint $table) {
            $table->id();
            $table->integer('marketing_id');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->string('kategori');
            $table->integer('level');
            $table->string('wilayah');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });

        // 4. Tabel Temp Operasional
        Schema::create('temp_operasional', function (Blueprint $table) {
            $table->id();
            $table->integer('marketing_id');
            $table->date('tanggal');
            $table->string('customer_nama');
            $table->string('customer_cp')->nullable();
            $table->string('kategori');
            $table->string('keterangan')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('temp_operasional');
        Schema::dropIfExists('temp_akomodasi');
        Schema::dropIfExists('biaya_operasional');
        Schema::dropIfExists('biaya_akomodasi');
    }
};
