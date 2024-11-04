<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use App\Models\jenisBarang;
use App\Models\Usaha;

class ProdukTest extends TestCase
{
    public function test_user_melihat_halaman_produk_setelah_login()
    {
        // Login the user
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com', // akun yang sudah terdaftar
            'password' => 'admin123',
        ]);

        $response->assertStatus(302);

        $response = $this->get('/produks');
        $response->assertStatus(200);
        $response->assertViewIs('produk.index');
    }

    public function test_store_creates_new_product()
    {
        // Login the user
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com', // akun yang sudah terdaftar
            'password' => 'admin123',
        ]);

        // Define valid product data
        $data = [
            'kode_produk' => '003',
            'nama_produk' => 'kripik_pisang',
            'id_jenis_barang' => 1,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect(route('produks.index'));
        $this->assertDatabaseHas('produks', $data);
    }
}
