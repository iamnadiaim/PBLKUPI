<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\login;
use App\Models\Usaha;
use App\Models\Produk;
use App\Models\jenisBarang;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProdukTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();

        $this->artisan('migrate:fresh --seed');

        $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);
    }
    public function test_user_melihat_halaman_produk_setelah_login()
    {
        $response = $this->get('/produks');
        $response->assertStatus(200);
        $response->assertViewIs('produk.index');
    }

    public function test_store_creates_new_product_with_valid_data()
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
    }

    public function test_store_creates_new_product_with_valid_data_letter_kode_produk()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => 'ABC',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);
    }

    public function test_store_creates_new_product_with_valid_data_letter_and_number_kode_produk()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => 'ABC',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $this->assertDatabaseHas('produks', $data);
    }

    public function test_store_creates_new_product_kode_produk_2_char()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => 'A2',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'kode_produk' => 'Kode Produk Minimal 3 Karakter'
        ]);
    }

    public function test_store_creates_new_product_kode_produk_15_char()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => 'A21AD32AW1E234A3',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'kode_produk' => 'Kode Produk Maksimal 15 Karakter'
        ]);
    }

    public function test_store_creates_new_product_kode_produk_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '',
            'nama_produk' => 'Sale Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'kode_produk' => 'The kode produk field is required.'
        ]);
    }

    public function test_store_creates_new_product_nama_produk_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => '',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'nama_produk' => 'The nama produk field is required.'
        ]);
    }

    public function test_store_creates_new_product_nama_produk_2_char()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'ab',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'nama_produk' => 'Nama Produk Minimal 3 Karakter'
        ]);
    }

    public function test_store_creates_new_product_nama_produk_20_char()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'abasdasdasdasdasdasda',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'nama_produk' => 'Nama Produk Maksimal 20 Karakter'
        ]);
    }

    public function test_store_creates_new_product_jenis_produk_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => '',
            'ukuran' => '200gr',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'id_jenis_barang' => 'The id jenis barang field is required.'
        ]);
    }

    public function test_store_creates_new_product_ukuran_produk_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'ukuran' => 'The ukuran field is required.'
        ]);
    }


    public function test_store_creates_new_product_ukuran_produk_invalid()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '90?',
            'harga' => 15000,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'ukuran' => 'Ukuran produk tidak sesuai'
        ]);
    }

    public function test_store_creates_new_product_harga_produk_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => '',
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'harga' => 'The harga field is required.'
        ]);
    }

    public function test_store_creates_new_product_harga_produk_format_letter()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => 'limapuluh',
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'harga' => 'Harga Produk Harus Angka'
        ]);
    }

    public function test_store_creates_new_product_harga_produk_invalid_format()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => '9?',
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'harga' => 'Harga Produk Harus Angka'
        ]);
    }

    public function test_store_creates_new_product_harga_produk_minus()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => -99,
            'stok' => 111,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'harga' => 'Harga Produk Harus diatas 0'
        ]);
    }

    public function test_store_creates_new_product_stok_unfilled()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => 99,
            'stok' => '',
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'stok' => 'The stok field is required.'
        ]);
    }

    public function test_store_creates_new_product_stok_invalid_format()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => 99,
            'stok' => 'sepuluh',
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertSessionHasErrors([
            'stok' => 'Stok Produk Harus Angka'
        ]);
    }

    public function test_update_product()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => 99,
            'stok' => 10,
        ];

        $dataUpdate = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Buah Naga',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '20gram',
            'harga' => 10000,
            'stok' => 20,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');

        $createdProduct = Produk::where('kode_produk', $data['kode_produk'])->first();
        $this->assertDatabaseHas('produks', $data);

        $response = $this->put(route('produk.update', $createdProduct->id), $dataUpdate);
        $response->assertRedirect('/produks');
        $this->assertDatabaseHas('produks', [
            'id' => $createdProduct->id,
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Buah Naga',
            'harga' => '10000', 
            'stok' => 30,        
        ]);
    }

    public function test_delete_product()
    {
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        $data = [
            'kode_produk' => '231as',
            'nama_produk' => 'Selai Pisang',
            'id_jenis_barang' => $jenisBarang->id,
            'ukuran' => '200gram',
            'harga' => 99,
            'stok' => 10,
        ];

        $response = $this->post(route('produks.store'), $data);
        $response->assertRedirect('/produks?highlight=1');
        $id = Produk::where('kode_produk', $data['kode_produk'])->first()->id;

        $response = $this->delete(route('produks.destroy', $id));
        $this->assertDatabaseMissing('produks', $data);
    }

    public function test_jenis_barang_product_invalid_format_number()
    {
        $jenisBarang =[
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 000,
        ]; 

        $response = $this->post(route('jenisbarangs.store'), $jenisBarang);
        $response->assertSessionHasErrors([
            'nama' => 'Nama jenis barang harus berupa huruf'
        ]);       
    }

    public function test_add_jenis_barang_product_succes()
    {
        $jenisBarang =[
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]; 

        $response = $this->post(route('jenisbarangs.store'), $jenisBarang);
        $this->assertDatabaseHas('jenis_barangs', $jenisBarang);      
    }

    public function test_jenis_barang_product_invalid_format_special_char()
    {
        $jenisBarang =[
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => '#Makanan',
        ]; 

        $response = $this->post(route('jenisbarangs.store'), $jenisBarang);
        $response->assertSessionHasErrors([
            'nama' => 'Nama jenis barang harus berupa huruf'
        ]);       
    }

    public function test_jenis_barang_product_double_data()
    {
        JenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]);

        $jenisBarang =[
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]; 

        $response = $this->post(route('jenisbarangs.store'), $jenisBarang);
        $response->assertSessionHasErrors([
            'nama' => 'Nama jenis barang sudah ada'
        ]);       
    }
}
