<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Beban;

class BebanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Beban::create([
            "tanggal" => "10-12-2023",
            "nama" => "tepung",
            "kategori" => "bahan baku",
            "jumlah" => "1",
            "total_biaya" => "1",
        ]);
    }
}