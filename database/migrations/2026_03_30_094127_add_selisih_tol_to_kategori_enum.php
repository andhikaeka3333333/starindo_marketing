<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom enum di tabel biaya_tols
        DB::statement("ALTER TABLE biaya_tols MODIFY COLUMN kategori ENUM('Top-Up Tol', 'Pemakaian Tol', 'Selisih Tol') NOT NULL");

        // Mengubah kolom enum di tabel temp_tols agar sinkron
        DB::statement("ALTER TABLE temp_tols MODIFY COLUMN kategori ENUM('Top-Up Tol', 'Pemakaian Tol', 'Selisih Tol') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan struktur enum seperti semula jika di-rollback
        // PERHATIAN: Rollback akan error jika di database sudah ada data dengan nilai 'Selisih Tol'.
        // Pastikan data tersebut dihapus atau diubah dulu sebelum melakukan rollback.

        DB::statement("ALTER TABLE biaya_tols MODIFY COLUMN kategori ENUM('Top-Up Tol', 'Pemakaian Tol') NOT NULL");

        DB::statement("ALTER TABLE temp_tols MODIFY COLUMN kategori ENUM('Top-Up Tol', 'Pemakaian Tol') NOT NULL");
    }
};
