<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pendapatan;

class PendapatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pendapatan::create([
            "tanggal" => "2023-12-09",
            "produk" => "Klemben",
            "jumlah_produk" => "2",
            "total" => "20000",
        ]);
    }
}
