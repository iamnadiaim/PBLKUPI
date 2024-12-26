<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Produk;
class produkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::create([
            "kode_produk" => "K001",
            "nama_produk" => "Nama Produk 1",
            "id_jenis_barang" => 1,
            "ukuran" => "10 gram",
            "harga" => 10000, // Contoh harga
            "stok" => 10,
        ]);
    }
}
