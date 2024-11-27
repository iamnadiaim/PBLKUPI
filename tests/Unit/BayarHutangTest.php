<?php

namespace Tests\Feature;

use App\Models\hutang;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;

class BayarHutangTest extends TestCase
{
    /** @test */
    public function it_displays_the_hutang_index_page()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat beberapa data hutang untuk pengguna ini
        hutang::factory()->count(3)->create([
            'id_usaha' => $user->id_usaha,
        ]);

        // Mengakses halaman daftar hutang
        $response = $this->get(route('hutang.index'));

        // Memastikan halaman dapat diakses
        $response->assertStatus(200);

        // Memastikan data hutang ditampilkan di halaman
        $hutangs = Hutang::where('id_usaha', $user->id_usaha)->get();
        foreach ($hutangs as $hutang) {
            $response->assertSee($hutang->nama);
            $response->assertSee($hutang->jumlah_hutang);
            $response->assertSee($hutang->jumlah_cicilan);
            $response->assertSee($hutang->catatan);
            $response->assertSee(Carbon::parse($hutang->tanggal_pinjaman)->format('Y-m-d'));
            $response->assertSee(Carbon::parse($hutang->tanggal_jatuh_tempo)->format('Y-m-d'));
            $response->assertSee($hutang->sisa_hutang);
            $response->assertSee($hutang->status);
        }
    }

    public function test_menampilkan_halaman_pembayaran_hutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1, // Tentukan 'id_usaha' untuk pengguna ini
        ]);
        $this->actingAs($user);

        // Membuat beberapa data hutang untuk pengguna ini
        $hutang = hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
        ]);

        // Mengirimkan permintaan GET ke route bayarhutang.create dengan parameter ID hutang
        $response = $this->get(route('bayarhutang.create', ['id' => $hutang->id]));

        // Memastikan respons memiliki status 200 (OK)
        $response->assertStatus(200);

        // Memastikan bahwa tampilan pembayaran.hutang ditampilkan
        $response->assertViewIs('pembayaran.hutang');
    }
    /** @test */
    public function test_menambah_pembayaran_hutang_data_valid()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'jumlah_cicilan' => 2,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran hutang yang valid
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 250000,
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa data pembayaran tersimpan di database
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Menghitung sisa hutang setelah pembayaran
        $sisaHutangExpected = $hutang->jumlah_hutang - $pembayaranData['jumlah'];
        
        // Memastikan bahwa sisa hutang diupdate dengan benar di database
        $hutang->refresh();
        $this->assertEquals($sisaHutangExpected, $hutang->sisa_hutang);

        // Memastikan pengguna diarahkan kembali ke route hutang.index dengan pesan sukses
        $response->assertRedirect(route('hutang.index'));
        $response->assertSessionHas('success', 'Pembayaran hutang berhasil disimpan.');
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_data_valid_dengan_tanggal_kemarin()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran hutang yang valid dengan tanggal kemarin
        $pembayaranData = [
            'tanggal_pembayaran' => now()->subDay()->format('Y-m-d'),  // Menggunakan tanggal kemarin
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,  // Jumlah pembayaran
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa data pembayaran tersimpan di database
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Menghitung sisa hutang setelah pembayaran
        $sisaHutangExpected = $hutang->jumlah_hutang - $pembayaranData['jumlah'];
        
        // Memastikan bahwa sisa hutang diupdate dengan benar di database
        $hutang->refresh();
        $this->assertEquals($sisaHutangExpected, $hutang->sisa_hutang);

        // Memastikan pengguna diarahkan kembali ke route hutang.index dengan pesan sukses
        $response->assertRedirect(route('hutang.index'));
        $response->assertSessionHas('success', 'Pembayaran hutang berhasil disimpan.');
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_data_invalid_dengan_tanggal_setelah_hari_ini()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Menentukan tanggal pembayaran yang lebih dari hari ini (invalid)
        $tanggalPembayaranInvalid = now()->addDay(2)->format('Y-m-d');  // Menambahkan 2 hari dari hari ini

        // Data pembayaran hutang yang valid dengan tanggal setelah hari ini (invalid)
        $pembayaranData = [
            'tanggal_pembayaran' => $tanggalPembayaranInvalid,  // Menggunakan tanggal yang lebih dari hari ini
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,  // Jumlah pembayaran
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa data pembayaran tidak disimpan di database
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Memastikan bahwa respons memiliki error untuk tanggal_pembayaran
        $response->assertSessionHasErrors(['tanggal_pembayaran']);
    }

    /** @test */
    public function test_pembayaran_hutang_dengan_nama_terisi_dan_hanya_bisa_dibaca()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'nama' => "nila",
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Mengakses halaman untuk pembayaran hutang
        $response = $this->get(route('bayarhutang.create', ['id' => $hutang->id]));
        
        // Memastikan halaman dapat diakses dengan status 200 OK
        $response->assertStatus(200);
        
        // Memastikan elemen form memiliki nama dan atribut readonly
        $response->assertSeeInOrder([
            'nama',  // Memastikan atribut nama ada
            'readonly',  // Memastikan atribut readonly ada pada field nama
        ]);

        // Data pembayaran yang valid
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,  // Nama pelanggan otomatis terisi
            'pembayaran' => 'cash',  
            'jumlah' => 200000,
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan pembayaran berhasil disimpan dan mengarahkan kembali ke daftar hutang
        $response->assertRedirect(route('hutang.index'));

        // Memastikan data pembayaran ada di database
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
        ]);

        // Memastikan bahwa sisa hutang berkurang sesuai jumlah pembayaran
        $hutang->refresh();
        $this->assertEquals($hutang->jumlah_hutang - $pembayaranData['jumlah'], $hutang->sisa_hutang);
    }

    /** @test */
    public function test_pembayaran_hutang_dengan_sisa_hutang_terisi_dan_hanya_bisa_dibaca()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'nama' => "nali",
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Mengakses halaman untuk pembayaran hutang
        $response = $this->get(route('bayarhutang.create', ['id' => $hutang->id]));
        
        // Memastikan halaman dapat diakses dengan status 200 OK
        $response->assertStatus(200);
        
        // Memastikan elemen form memiliki sisa_hutang dan atribut readonly
        $response->assertSeeInOrder([
            'sisa_hutang',  // Memastikan atribut sisa_hutang ada
            'readonly',  // Memastikan atribut readonly ada pada field sisa_hutang
        ]);

        // Data pembayaran yang valid
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,  // Nama pelanggan otomatis terisi
            'pembayaran' => 'cash',  
            'jumlah' => $hutang->sisa_hutang,
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan pembayaran berhasil disimpan dan mengarahkan kembali ke daftar hutang
        $response->assertRedirect(route('hutang.index'));

        // Memastikan data pembayaran ada di database
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
        ]);

        // Memastikan bahwa sisa hutang berkurang sesuai jumlah pembayaran
        $hutang->refresh();
        $this->assertEquals($hutang->jumlah_hutang - $pembayaranData['jumlah'], $hutang->sisa_hutang);
    }
    /** @test */
    public function test_menambah_pembayaran_hutang_data_invalid_dengan_metode_pembayaran_terlalu_singkat()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'TT',  // Kurang dari 4 karakter
            'jumlah' => 200000,
            'id' => $hutang->id,
        ];

        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);
        $response->assertSessionHasErrors([
            'pembayaran' => 'Cara pembayaran harus minimal 4 karakter.', // Pastikan sesuai dengan pesan validasi
        ]);      
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'pembayaran' => $pembayaranData['pembayaran'],
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_data_invalid_dengan_metode_pembayaran_terlalu_panjang()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank via Mobile App Payment Method',  // Lebih dari 20 karakter
            'jumlah' => 200000,
            'id' => $hutang->id,
        ];

        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);
        $response->assertSessionHasErrors([
            'pembayaran' => 'Cara pembayaran maksimal 20 karakter.', // Sesuaikan dengan pesan di validasi
        ]);
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'pembayaran' => $pembayaranData['pembayaran'],
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_data_invalid_dengan_nominal_lebih_dari_sisa_hutang()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 600000,  // Melebihi sisa hutang
            'id' => $hutang->id,
        ];

        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);
        $response->assertSessionHasErrors('jumlah');
        $response->assertSessionHas('errors');
        $this->assertTrue(session('errors')->has('jumlah'));
        $this->assertEquals('Jumlah melebihi sisa hutang', session('errors')->get('jumlah')[0]);
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'jumlah' => $pembayaranData['jumlah'],
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_dengan_tanggal_pembayaran_kosong()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        $pembayaranData = [
            'tanggal_pembayaran' => '',  // Tanggal kosong
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer',
            'jumlah' => 200000,
            'id' => $hutang->id,
        ];

        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);
        $response->assertSessionHasErrors(['tanggal_pembayaran']);
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_dengan_cara_pembayaran_kosong()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran hutang yang invalid (cara pembayaran kosong)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->toDateString(), // Mengisi tanggal pembayaran dengan hari ini
            'nama' => $hutang->nama,
            'pembayaran' => '', // Cara pembayaran dikosongkan
            'jumlah' => 200000,
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'pembayaran' karena kosong
        $response->assertSessionHasErrors(['pembayaran']);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_hutangs'
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_hutang_dengan_nominal_kosong()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 500000,
            'sisa_hutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran hutang yang invalid (nominal kosong)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->toDateString(), // Mengisi tanggal pembayaran dengan hari ini
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank', // Cara pembayaran valid
            'jumlah' => '', // Nominal dikosongkan
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'jumlah' karena kosong
        $response->assertSessionHasErrors(['jumlah']);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_hutangs'
        $this->assertDatabaseMissing('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambahkan_data_pembayaran_hutang_valid_dengan_jumlah_angsuran_2_kali()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayarkan dengan jumlah hutang yang bisa dibagi 2 angsuran
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 1000000,  // Total hutang yang harus dibayar
            'jumlah_cicilan' => 2,
            'sisa_hutang' => 1000000,    // Sisa hutang yang harus dibayar
            'status' => false,
        ]);

        // Pembayaran pertama, misalnya 500000
        $pembayaranData1 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 500000,  // Angsuran pertama
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran pertama
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData1);

        // Memastikan bahwa pembayaran pertama berhasil
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'jumlah' => $pembayaranData1['jumlah'],
        ]);

        // Memastikan bahwa sisa hutang berkurang setelah pembayaran pertama
        $hutang->refresh();
        $this->assertEquals(500000, $hutang->sisa_hutang); // Sisa hutang setelah pembayaran pertama

        // Pembayaran kedua, misalnya 500000
        $pembayaranData2 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 500000,  // Angsuran kedua
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran kedua
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData2);

        // Memastikan bahwa pembayaran kedua berhasil
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'jumlah' => $pembayaranData2['jumlah'],
        ]);

        // Memastikan bahwa hutang lunas setelah pembayaran kedua
        $hutang->refresh();
        $this->assertEquals(0, $hutang->sisa_hutang);  // Memastikan sisa hutang menjadi 0

        // Periksa apakah status hutang berubah menjadi lunas
        $this->assertEquals(1, $hutang->status);  // Status hutang harus 1 (lunas)
    }

    public function test_menambahkan_data_pembayaran_hutang_valid_dengan_jumlah_angsuran_1dari2_lunas()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data hutang yang akan dibayar dengan jumlah hutang yang bisa dibagi 2 angsuran
        $hutang = Hutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_hutang' => 1000000,  // Total hutang yang harus dibayar
            'jumlah_cicilan' => 2,
            'sisa_hutang' => 1000000,    // Sisa hutang yang harus dibayar
            'status' => false,
        ]);

        // Pembayaran pertama, langsung dilunasi
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $hutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 1000000,  // Angsuran pertama dan langsung dilunasi
            'id' => $hutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran pertama yang langsung dilunasi
        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData);

        // Memastikan bahwa pembayaran berhasil dan dialihkan ke halaman hutang
        $response->assertRedirect(route('hutang.index'));
        $this->assertDatabaseHas('bayar_hutangs', [
            'id_hutang' => $hutang->id,
            'jumlah' => $pembayaranData['jumlah'],
        ]);

        // Memastikan bahwa sisa hutang menjadi 0 setelah pembayaran pertama
        $hutang->refresh();
        $this->assertEquals(0, $hutang->sisa_hutang); // Sisa hutang harus 0 setelah pembayaran

        // Memastikan bahwa status hutang berubah menjadi lunas (1)
        $this->assertEquals(1, $hutang->status);  // Status hutang harus 1 (lunas)
    }

    public function test_menambahkan_data_pembayaran_hutang_dengan_jumlah_kurang_dari_sisa_hutang()
{
    // Membuat pengguna dan mengautentikasi mereka
    $user = User::factory()->create([
        'id_usaha' => 1,
    ]);
    $this->actingAs($user);

    // Membuat data hutang dengan jumlah hutang yang bisa dibagi 2 angsuran
    $hutang = Hutang::factory()->create([
        'id_usaha' => $user->id_usaha,
        'jumlah_hutang' => 1000000,  // Total hutang
        'jumlah_cicilan' => 2,
        'sisa_hutang' => 1000000,    // Sisa hutang
        'status' => false,
    ]);

    // Pembayaran pertama
    $pembayaranData1 = [
        'tanggal_pembayaran' => now()->format('Y-m-d'),
        'nama' => $hutang->nama,
        'pembayaran' => 'Transfer Bank',
        'jumlah' => 500000,  // Angsuran pertama
        'id' => $hutang->id,
    ];

    // Mengirimkan permintaan POST untuk pembayaran pertama
    $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData1);

    // Memastikan bahwa pembayaran pertama berhasil
    $response->assertRedirect(route('hutang.index'));
    $this->assertDatabaseHas('bayar_hutangs', [
        'id_hutang' => $hutang->id,
        'jumlah' => $pembayaranData1['jumlah'],
    ]);

    // Memastikan bahwa sisa hutang berkurang setelah pembayaran pertama
    $hutang->refresh();
    $this->assertEquals(500000, $hutang->sisa_hutang); // Sisa hutang setelah pembayaran pertama

    // Pembayaran kedua (pembayaran terakhir) dengan jumlah kurang dari sisa hutang
    $pembayaranData2 = [
        'tanggal_pembayaran' => now()->format('Y-m-d'),
        'nama' => $hutang->nama,
        'pembayaran' => 'Transfer Bank',
        'jumlah' => 400000, // Kurang dari sisa hutang
        'id' => $hutang->id,
    ];

    // Mengirimkan permintaan POST untuk pembayaran kedua
    $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), $pembayaranData2);

    // Memastikan bahwa validasi gagal untuk pembayaran terakhir yang kurang dari sisa hutang
    $response->assertSessionHasErrors([
        'jumlah' => 'Kesalahan: Pembayaran terakhir harus sama dengan sisa hutang.',
    ]);

    // Memastikan bahwa data pembayaran tidak disimpan di database
    $this->assertDatabaseMissing('bayar_hutangs', [
        'id_hutang' => $hutang->id,
        'jumlah' => $pembayaranData2['jumlah'],
    ]);

    // Memastikan sisa hutang tidak berubah setelah pembayaran gagal
    $hutang->refresh();
    $this->assertEquals(500000, $hutang->sisa_hutang);  // Sisa hutang tetap sama
}
}