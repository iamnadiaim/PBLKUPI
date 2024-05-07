<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BayarPiutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bayarpiutang = [
            [
                'id_piutang' => 1,
                'nama' => 'John Doe',
                'pembayaran' => 'Transfer Bank',
                'jumlah' => 500,
                'id_usaha' => 1,
            ],
            [
                'id_piutang' => 2,
                'nama' => 'Jane Smith',
                'pembayaran' => 'Tunai',
                'jumlah' => 300,
                'id_usaha' => null,
            ],
            // Tambahkan data pembayaran hutang lainnya sesuai kebutuhan
        ];

        // Loop melalui data pembayaran hutang dan masukkan ke database
        foreach ($bayarpiutang as $data) {
            BayarPiutang::create($data);
        }
    }
}
