<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use App\Models\Produk;
use App\Models\jenisBarang;

class ITProdukTest extends TestCase
{
    /** @test */
    public function dapat_menampilkan_halaman_produk()
    {
        // Membuat user dan mengautentikasi
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengakses halaman produk.index tanpa data produk
        $response = $this->get(route('produks.index'));

        // Memastikan halaman dapat diakses
        $response->assertStatus(200);

    }

/** @test */
public function dapat_menambahkan_jenis_barang_dan_produk_baru()
{
     // Membuat user dan mengautentikasi
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

    // Data jenis barang yang akan ditambahkan
    $jenisBarangData = [
        'nama' => 'Jenis Barang Baru',
        'id_usaha' => $user->id_usaha,
    ];

    // Menambahkan jenis barang
    $jenisBarangResponse = $this->post(route('jenisbarangs.store'), $jenisBarangData);

    // Memastikan jenis barang berhasil ditambahkan
    $jenisBarangResponse->assertStatus(302); // Redirect setelah berhasil
    $this->assertDatabaseHas('jenis_barangs', $jenisBarangData);

    // Mengambil data jenis barang yang baru ditambahkan
    $jenisBarang = jenisBarang::where('nama', 'Jenis Barang Baru')->first();

    // Data produk baru yang akan ditambahkan
    $produkData = [
        'kode_produk' => 'PROD001',
        'nama_produk' => 'Produk Baru',
        'id_jenis_barang' => $jenisBarang->id,
        'ukuran' => 'M',
        'harga' => 10000,
        'stok' => 50,
        'id_usaha' => $user->id_usaha,
    ];

    // Menambahkan produk baru
    $produkResponse = $this->post(route('produks.store'), $produkData);

    // Memastikan produk berhasil ditambahkan
    $produkResponse->assertStatus(302); // Redirect setelah berhasil
    $this->assertDatabaseHas('produks', $produkData);

    // Memastikan pesan sukses ada di session
    $produkResponse->assertSessionHas('success', 'Produk berhasil ditambahkan');
   
}

    /** @test */
public function dapat_menampilkan_halaman_produk_index_dan_menambahkan_dan_mengedit_produk()
{
    // Membuat user dan mengautentikasi
    $user = User::factory()->create([
        'id_usaha' => 1,
    ]);
    $this->actingAs($user);

    // Membuat jenis barang
    $jenisBarang = jenisBarang::factory()->create([
        'id_usaha' => $user->id_usaha
    ]);

    // 1. Menampilkan halaman produk index
    $response = $this->get(route('produks.index'));
    $response->assertStatus(200);

    // 2. Menambahkan produk baru
    $produkData = [
        'kode_produk' => 'PROD003',
        'nama_produk' => 'Produk Test',
        'id_jenis_barang' => $jenisBarang->id,
        'ukuran' => 'L',
        'harga' => 20000,
        'stok' => 50,
        'id_usaha' => $user->id_usaha,
    ];

    // Mengirimkan request untuk menambah produk
    $produkResponse = $this->post(route('produks.store'), $produkData);
    
    // Memastikan status berhasil dan redirect ke halaman produk index
    $produkResponse->assertStatus(302);
    $produkResponse->assertRedirect(route('produks.index'));

    // Memastikan produk baru ada di database
    $this->assertDatabaseHas('produks', $produkData);

    // Memastikan ada pesan sukses
    $produkResponse->assertSessionHas('success', 'Produk berhasil ditambahkan');

    // 3. Mengedit produk yang baru ditambahkan
    $produk = Produk::where('kode_produk', 'PROD003')->first(); // Ambil produk yang baru ditambahkan
    $produkEditData = [
        'nama_produk' => 'Produk Test Diupdate',
        'ukuran' => 'XL',
        'harga' => 25000,
        'stok' => 150,  // Perbarui stok sesuai yang diinginkan
    ];

    // Mengambil halaman edit produk
    $editResponse = $this->get(route('produks.edit', $produk->id));
    
    // Memastikan halaman edit dimuat dengan status 200
    $editResponse->assertStatus(200);

    // Mengirimkan request untuk mengedit produk
    $updateResponse = $this->put(route('produk.update', $produk->id), $produkEditData);
    
    // Memastikan status berhasil dan redirect ke halaman produk index
    $updateResponse->assertStatus(302);
    $updateResponse->assertRedirect(route('produks.index'));


    // Memastikan pesan sukses ada di session
    $updateResponse->assertSessionHas('success', 'Produk berhasil diperbarui');
}

/** @test */
public function menampilkam_view_produk_menambahkan_mengedit_mengahapus_produk()
{
    // Membuat user dan mengautentikasi
    $user = User::factory()->create([
        'id_usaha' => 1,
    ]);
    $this->actingAs($user);

    // Membuat jenis barang
    $jenisBarang = jenisBarang::factory()->create([
        'id_usaha' => $user->id_usaha
    ]);

    // 1. Menampilkan halaman produk index
    $response = $this->get(route('produks.index'));
    $response->assertStatus(200); // Pastikan halaman produk index berhasil ditampilkan

    // 2. Menambahkan produk baru
    $produkData = [
        'kode_produk' => 'PROD004',
        'nama_produk' => 'Produk Test',
        'id_jenis_barang' => $jenisBarang->id,
        'ukuran' => 'L',
        'harga' => 20000,
        'stok' => 50,
        'id_usaha' => $user->id_usaha,
    ];

    // Mengirimkan request untuk menambah produk
    $produkResponse = $this->post(route('produks.store'), $produkData);
    
    // Memastikan status berhasil dan redirect ke halaman produk index
    $produkResponse->assertStatus(302);
    $produkResponse->assertRedirect(route('produks.index'));

    // Memastikan produk baru ada di database
    $this->assertDatabaseHas('produks', $produkData);

    // Memastikan ada pesan sukses
    $produkResponse->assertSessionHas('success', 'Produk berhasil ditambahkan');

    // 3. Mengedit produk yang baru ditambahkan
    $produk = Produk::where('kode_produk', 'PROD004')->first(); // Ambil produk yang baru ditambahkan
    $produkEditData = [
        'nama_produk' => 'Produk Test Diuptudate',
        'ukuran' => 'XL',
        'harga' => 25000,
        'stok' => 150,  // Perbarui stok sesuai yang diinginkan
    ];

    // Mengambil halaman edit produk
    $editResponse = $this->get(route('produks.edit', $produk->id));
    
    // Memastikan halaman edit dimuat dengan status 200
    $editResponse->assertStatus(200);

    // Mengirimkan request untuk mengedit produk
    $updateResponse = $this->put(route('produk.update', $produk->id), $produkEditData);
    
    // Memastikan status berhasil dan redirect ke halaman produk index
    $updateResponse->assertStatus(302);
    $updateResponse->assertRedirect(route('produks.index'));

    // Memastikan pesan sukses ada di session
    $updateResponse->assertSessionHas('success', 'Produk berhasil diperbarui');

    // 4. Menghapus produk
    // Mengambil produk yang sudah ada
    $produkToDelete = Produk::where('kode_produk', 'PROD004')->first();

    // Mengirimkan request untuk menghapus produk
    $deleteResponse = $this->delete(route('produks.destroy', $produkToDelete->id));

    // Memastikan produk berhasil dihapus dan redirect kembali
    $deleteResponse->assertStatus(302);
    $deleteResponse->assertRedirect('/produks?highlight=1');
    
    // Memastikan produk sudah tidak ada di database
    $this->assertDatabaseMissing('produks', ['id' => $produkToDelete->id]);

    // Memastikan ada pesan sukses
    $deleteResponse->assertSessionHas('destroy', 'Produk berhasil dihapus');

}

}