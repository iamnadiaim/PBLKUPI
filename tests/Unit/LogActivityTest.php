<?php

namespace Tests\Unit;

use App\Models\Produk;
use Tests\TestCase;
use App\Models\jenisBarang;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogActivityTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void{
        parent::setUp();

        $this->artisan('migrate:fresh --seed');

        $this->post(route('login'), [
            'email' => 'pegawai@gmail.com',
            'password' => 'pegawai123',
        ]);
    }

    public function test_log_activity_pegawai()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '010',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);

        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');

        $response = $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response = $this->get(route('daftarPegawai'));
        $response->assertSeeText('pegawai');
        $response->assertSeeText('Menambahkan Produk -');
        $response->assertSeeText('Sale Pisang');
    }

    public function test_klik_log_activity_pegawai_add_product()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '010',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);

        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');

        $response = $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response = $this->get(route('daftarPegawai'));
        $response->assertSeeText('pegawai');
        $response->assertSeeText('Menambahkan Produk -');
        $response->assertSeeText('Sale Pisang');

        $response = $this->get('produks?highlight=1');
        $response->assertSeeText('Sale Pisang');
    }

    public function test_klik_log_activity_pegawai_add_pendapatan()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '010',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);
        $product = Produk::where('nama_produk', 'Sale Pisang')->first();

        $dataPendapatan = [
            "tanggal" => "2024-11-11",
            "id_produk" => $product->id,
            "jumlah_produk" => "2",
            "nama_pembeli" => "asd",
            "id_usaha" => auth()->user()->id_usaha,
            "harga_produk" => "15000",
            "total" => 30000
        ];
        $response = $this->post(route('pendapatan.store'), $dataPendapatan);
        $response->assertRedirect('/riwayat?highlight=1');
        $this->assertDatabaseHas('pendapatans', $dataPendapatan);

        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');

        $response = $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response = $this->get(route('daftarPegawai'));
        $response->assertSeeText('pegawai');
        $response->assertSeeText('Menambahkan Transaksi -');
        $response->assertSeeText('Pendapatan');

        $response = $this->get('riwayat?highlight=1');
        //nama pembeli
        $response->assertSeeText('asd');
        //jumlah produk
        $response->assertSeeText('2');
    }

    public function test_klik_log_activity_pegawai_update_product()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '010',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);

        $product = Produk::where('nama_produk', 'Sale Pisang')->first();

        $dataUpdate = [
            'kode_produk' => '010',
            'nama_produk' => 'Pisang Goreng',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '20gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->put(route('produk.update', $product->id), $dataUpdate);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', [
            'nama_produk' => 'Pisang Goreng',
            'ukuran' => '20gr',
            'stok' => 222

        ]);

        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');

        $response = $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response = $this->get(route('daftarPegawai'));
        $response->assertSeeText('pegawai');
        $response->assertSeeText('Mengubah data Produk -');
        $response->assertSeeText('Pisang Goreng');

        $response = $this->get('produks?highlight=1');
        $response->assertSeeText('Pisang Goreng');
    }

    public function test_klik_log_activity_pegawai_delete_product()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '010',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);

        $product = Produk::where('nama_produk', 'Sale Pisang')->first();
        $response = $this->delete(route('produks.destroy', $product->id));

        $response = $this->get(route('logout'));
        $response->assertRedirect('/login');

        $response = $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response = $this->get(route('daftarPegawai'));
        $response->assertSeeText('pegawai');
        $response->assertSeeText('Menghapus Produk -');
        $response->assertSeeText('Sale Pisang');
    }


}
