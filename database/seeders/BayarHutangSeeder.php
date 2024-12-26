<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BayarHutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bayarhutang = [
            [
                'id_hutang' => 1,
                'nama' => 'John Doe',
                'pembayaran' => 'Transfer Bank',
                'jumlah' => 500,
                'id_usaha' => 1,
            ],
            [
                'id_hutang' => 2,
                'nama' => 'Jane Smith',
                'pembayaran' => 'Tunai',
                'jumlah' => 300,
                'id_usaha' => null,
            ],
            // Tambahkan data pembayaran hutang lainnya sesuai kebutuhan
        ];

        // Loop melalui data pembayaran hutang dan masukkan ke database
        foreach ($bayarhutang as $data) {
            BayarHutang::create($data);
        }
    }
}
