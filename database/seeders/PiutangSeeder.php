<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PiutangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Replace with your actual data
        $piutangs = [
            [
                // 'id_kategori_hutang' => 1, // Replace with actual category ID
                'nama' => 'Piutang Sewa Kantor',
                'catatan' => 'Piutang untuk Sewa Kantor',
                'tanggal_pinjaman' => '2024-04-01',
                'tanggal_jatuh_tempo' => '2024-04-01',
                'jumlah_piutang' => 10000000,
                'jumlah_cicilan' => 10000000,
                'sisa_piutang' => 10000000,
                // 'pembayaran' => null,
                // 'status' => 'unpaid',
            ],
            [
                // 'id_kategori_hutang' => 2, // Replace with actual category ID
                'nama' => 'Piutang Perlengkapan Kantor',
                'catatan' => 'Piutang untuk Sewa Kantor',
                'tanggal_pinjaman' => '2024-04-01',
                'tanggal_jatuh_tempo' => '2024-04-01',
                'jumlah_piutang' => 10000000,
                'jumlah_cicilan' => 10000000,
                'sisa_piutang' => 10000000,
                // 'pembayaran' => 'Pembayaran sebagian',
                // 'status' => 'partially_paid',
            ],
        ];

        // Insert data into the hutangs table
        DB::table('piutangs')->insert($piutangs);
    }
}
