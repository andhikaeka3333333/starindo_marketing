<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TarifPerjalanan;

class TarifPerjalananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // --- KATEGORI: UANG MAKAN (UM) ---
            ['kategori' => 'UM', 'wilayah' => 'Jabotabek', 'level' => 1, 'nominal' => 300000],
            ['kategori' => 'UM', 'wilayah' => 'Jabotabek', 'level' => 2, 'nominal' => 250000],
            ['kategori' => 'UM', 'wilayah' => 'Jabotabek', 'level' => 3, 'nominal' => 200000],

            ['kategori' => 'UM', 'wilayah' => 'Lainnya', 'level' => 1, 'nominal' => 200000],
            ['kategori' => 'UM', 'wilayah' => 'Lainnya', 'level' => 2, 'nominal' => 150000],
            ['kategori' => 'UM', 'wilayah' => 'Lainnya', 'level' => 3, 'nominal' => 100000],

            // --- KATEGORI: HOTEL ---
            ['kategori' => 'Hotel', 'wilayah' => 'Jabotabek', 'level' => 1, 'nominal' => 900000],
            ['kategori' => 'Hotel', 'wilayah' => 'Jabotabek', 'level' => 2, 'nominal' => 750000],
            ['kategori' => 'Hotel', 'wilayah' => 'Jabotabek', 'level' => 3, 'nominal' => 600000],

            ['kategori' => 'Hotel', 'wilayah' => 'Lainnya', 'level' => 1, 'nominal' => 750000],
            ['kategori' => 'Hotel', 'wilayah' => 'Lainnya', 'level' => 2, 'nominal' => 600000],
            ['kategori' => 'Hotel', 'wilayah' => 'Lainnya', 'level' => 3, 'nominal' => 400000],
        ];

        foreach ($data as $item) {
            TarifPerjalanan::updateOrCreate(
                [
                    'kategori' => $item['kategori'],
                    'wilayah' => $item['wilayah'],
                    'level' => $item['level']
                ],
                ['nominal' => $item['nominal']]
            );
        }
    }
}
