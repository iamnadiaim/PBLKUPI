<?php

namespace Tests\Unit;

use App\Models\piutang;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;

class BayarPiutangTest extends TestCase
{

    /** @test */
    public function it_displays_the_daftar_piutang_page()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
        'id_usaha' => 1, // Tentukan 'id_usaha' untuk pengguna ini
        ]);
        $this->actingAs($user);

        // Membuat beberapa data piutang untuk pengguna ini
        piutang::factory()->count(3)->create([
            'id_usaha' => $user->id_usaha,
        ]);

        // Mengakses halaman daftar piutang
        $response = $this->get(route('piutang.index'));

        // Memastikan halaman dapat diakses
        $response->assertStatus(200);

        // Memastikan data piutang ditampilkan di halaman
        $piutangs = piutang::all();
        foreach ($piutangs as $piutang) {
            $response->assertSee($piutang->nama);
            $response->assertSee($piutang->jumlah_piutang);
            $response->assertSee($piutang->jumlah_cicilan);
            $response->assertSee($piutang->catatan);
            $response->assertSee(\Carbon\Carbon::parse($piutang->tanggal_pinjaman)->format('Y-m-d'));
            $response->assertSee(\Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('Y-m-d'));
            $response->assertSee($piutang->sisa_piutang);
            $response->assertSee($piutang->status);
        }
    }
    
    public function test_menampilkan_halaman_pembayaran_piutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1, // Tentukan 'id_usaha' untuk pengguna ini
        ]);
        $this->actingAs($user);
    
        // Membuat beberapa data piutang untuk pengguna ini
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
        ]);
    
        // Mengirimkan permintaan GET ke route bayarpiutang.index dengan parameter ID piutang
        $response = $this->get(route('bayarpiutang.create', ['id' => $piutang->id]));
    
        // Memastikan respons memiliki status 200 (OK)
        $response->assertStatus(200);
    
        // Memastikan bahwa tampilan pembayaran.piutang ditampilkan
        $response->assertViewIs('pembayaran.piutang');
    }

    /** @test */
    public function test_menambah_pembayaran_piutang_data_valid()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);
        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);
        // Data pembayaran piutang yang valid
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];
        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);
        // Memastikan bahwa data pembayaran tersimpan di database
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Menghitung sisa piutang setelah pembayaran
        $sisaPiutangExpected = $piutang->jumlah_piutang - $pembayaranData['jumlah'];
        
        // Memastikan bahwa sisa piutang diupdate dengan benar di database
        $piutang->refresh();
        $this->assertEquals($sisaPiutangExpected, $piutang->sisa_piutang);

        // Memastikan pengguna diarahkan kembali ke route piutang.index dengan pesan sukses
        $response->assertRedirect(route('piutang.index'));
        $response->assertSessionHas('success', 'Pembayaran piutang berhasil disimpan.');
    }

    /** @test */
    public function test_menambah_pembayaran_piutang_data_valid_dengan_tanggal_kemarin()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang yang valid dengan tanggal kemarin
        $pembayaranData = [
            'tanggal_pembayaran' => now()->subDay()->format('Y-m-d'),  // Menggunakan tanggal kemarin
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,  // Jumlah pembayaran
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa data pembayaran tersimpan di database
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Menghitung sisa piutang setelah pembayaran
        $sisaPiutangExpected = $piutang->jumlah_piutang - $pembayaranData['jumlah'];
        
        // Memastikan bahwa sisa piutang diupdate dengan benar di database
        $piutang->refresh();
        $this->assertEquals($sisaPiutangExpected, $piutang->sisa_piutang);

        // Memastikan pengguna diarahkan kembali ke route piutang.index dengan pesan sukses
        $response->assertRedirect(route('piutang.index'));
        $response->assertSessionHas('success', 'Pembayaran piutang berhasil disimpan.');
    }

    /** @test */
    public function test_menambah_pembayaran_piutang_data_invalid_dengan_tanggal_setelah_hari_ini()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Menentukan tanggal pembayaran yang lebih dari hari ini (invalid)
        $tanggalPembayaranInvalid = now()->addDay(2)->format('Y-m-d');  // Menambahkan 2 hari dari hari ini

        // Data pembayaran piutang yang valid dengan tanggal setelah hari ini (invalid)
        $pembayaranData = [
            'tanggal_pembayaran' => $tanggalPembayaranInvalid,  // Menggunakan tanggal yang lebih dari hari ini
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,  // Jumlah pembayaran
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa data pembayaran tidak disimpan di database
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'nama' => $pembayaranData['nama'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

         // Memastikan bahwa respons memiliki error untuk tanggal_pembayaran
         $response->assertSessionHasErrors(['tanggal_pembayaran' ]);
    }

  /** @test */
  public function test_pembayaran_piutang_dengan_nama_terisi_dan_hanya_bisa_dibaca()
  {
      // Membuat pengguna dan mengautentikasi mereka
      $user = User::factory()->create([
          'id_usaha' => 1,
      ]);
      $this->actingAs($user);
  
      // Membuat data piutang yang akan dibayarkan
      $piutang = piutang::factory()->create([
          'id_usaha' => $user->id_usaha,
          'nama' => "nila",
          'jumlah_piutang' => 500000,
          'sisa_piutang' => 500000,
          'status' => false,
      ]);
  
      // Mengakses halaman untuk pembayaran piutang
      $response = $this->get(route('bayarpiutang.create', ['id' => $piutang->id]));
      
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
          'nama' => $piutang->nama,  // Nama pelanggan otomatis terisi
          'pembayaran' => 'cash',  
          'jumlah' => 200000,
          'id' => $piutang->id,
      ];
  
      // Mengirimkan permintaan POST untuk menyimpan data pembayaran
      $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);
  
      // Memastikan pembayaran berhasil disimpan dan mengarahkan kembali ke daftar piutang
      $response->assertRedirect(route('piutang.index'));
  
      // Memastikan data pembayaran ada di database
      $this->assertDatabaseHas('bayar_piutangs', [
          'id_piutang' => $piutang->id,
          'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
          'nama' => $pembayaranData['nama'],
          'pembayaran' => $pembayaranData['pembayaran'],
          'jumlah' => $pembayaranData['jumlah'],
      ]);
  
      // Memastikan bahwa sisa piutang berkurang sesuai jumlah pembayaran
      $piutang->refresh();
      $this->assertEquals($piutang->jumlah_piutang - $pembayaranData['jumlah'], $piutang->sisa_piutang);
  }

  /** @test */
  public function test_pembayaran_piutang_dengan_sisa_piutang_terisi_dan_hanya_bisa_dibaca()
  {
      // Membuat pengguna dan mengautentikasi mereka
      $user = User::factory()->create([
          'id_usaha' => 1,
      ]);
      $this->actingAs($user);
  
      // Membuat data piutang yang akan dibayarkan
      $piutang = piutang::factory()->create([
          'id_usaha' => $user->id_usaha,
          'nama' => "nali",
          'jumlah_piutang' => 500000,
          'sisa_piutang' => 500000,
          'status' => false,
      ]);
  
      // Mengakses halaman untuk pembayaran piutang
      $response = $this->get(route('bayarpiutang.create', ['id' => $piutang->id]));
      
      // Memastikan halaman dapat diakses dengan status 200 OK
      $response->assertStatus(200);
      
      // Memastikan elemen form memiliki nama dan atribut readonly
      $response->assertSeeInOrder([
          'sisa_piutang',  // Memastikan atribut nama ada
          'readonly',  // Memastikan atribut readonly ada pada field nama
      ]);
  
      // Data pembayaran yang valid
      $pembayaranData = [
          'tanggal_pembayaran' => now()->format('Y-m-d'),
          'nama' => $piutang->nama,  // Nama pelanggan otomatis terisi
          'pembayaran' => 'cash',  
          'jumlah' => $piutang->sisa_piutang,
          'id' => $piutang->id,
      ];
  
      // Mengirimkan permintaan POST untuk menyimpan data pembayaran
      $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);
  
      // Memastikan pembayaran berhasil disimpan dan mengarahkan kembali ke daftar piutang
      $response->assertRedirect(route('piutang.index'));
  
      // Memastikan data pembayaran ada di database
      $this->assertDatabaseHas('bayar_piutangs', [
          'id_piutang' => $piutang->id,
          'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
          'nama' => $pembayaranData['nama'],
          'pembayaran' => $pembayaranData['pembayaran'],
          'jumlah' => $pembayaranData['jumlah'],
      ]);
  
      // Memastikan bahwa sisa piutang berkurang sesuai jumlah pembayaran
      $piutang->refresh();
      $this->assertEquals($piutang->jumlah_piutang - $pembayaranData['jumlah'], $piutang->sisa_piutang);
  }
  

    /** @test */
    public function test_menambah_pembayaran_piutang_data_invalid_dengan_metode_pembayaran_terlalu_singkat()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang dengan cara pembayaran yang invalid (kurang dari 4 karakter)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'TT',  // Cara pembayaran dengan panjang kurang dari 4 karakter
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        $response->assertSessionHasErrors([
            'pembayaran' => 'Cara pembayaran harus minimal 4 karakter.', // Pastikan sesuai dengan pesan validasi
        ]);  
        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_piutang_data_invalid_dengan_metode_pembayaran_terlalu_panjang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang dengan cara pembayaran yang invalid (lebih dari 20 karakter)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank via Mobile App Payment Method',  // Cara pembayaran lebih dari 20 karakter
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);
        $response->assertSessionHasErrors([
            'pembayaran' => 'Cara pembayaran maksimal 20 karakter.', // Sesuaikan dengan pesan di validasi
        ]);
        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    public function test_menambah_pembayaran_piutang_data_invalid_dengan_nominal_lebih_dari_sisa_piutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,  // Sisa piutang yang tersedia
            'status' => false,
        ]);

        // Data pembayaran piutang dengan nominal yang lebih besar dari sisa piutang
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 600000,  // Nominal pembayaran melebihi sisa piutang
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'jumlah' karena pembayaran lebih besar dari sisa piutang
        $response->assertSessionHasErrors('jumlah');  // Pastikan error validasi pada jumlah pembayaran muncul

        // Memastikan bahwa pesan error yang benar muncul di session
        $response->assertSessionHas('errors');
        $this->assertTrue(session('errors')->has('jumlah'));
        $this->assertEquals('Jumlah melebihi sisa piutang', session('errors')->get('jumlah')[0]);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);

        // Memastikan bahwa sisa piutang tidak berubah
        $piutang->refresh();
        $this->assertEquals(500000, $piutang->sisa_piutang);
    }


    /** @test */
    public function test_menambah_pembayaran_piutang_dengan_tanggal_pembayaran_kosong()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang yang invalid (tanggal pembayaran kosong)
        $pembayaranData = [
            'tanggal_pembayaran' => '', // Tanggal pembayaran dikosongkan
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer',
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'tanggal_pembayaran' karena kosong
        $response->assertSessionHasErrors(['tanggal_pembayaran']);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambah_pembayaran_piutang_dengan_cara_pembayaran_kosong()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang yang invalid (cara pembayaran kosong)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->toDateString(), // Mengisi tanggal pembayaran dengan hari ini
            'nama' => $piutang->nama,
            'pembayaran' => '', // Cara pembayaran dikosongkan
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'pembayaran' karena kosong
        $response->assertSessionHasErrors(['pembayaran']);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }
    /** @test */
    public function test_menambah_pembayaran_piutang_dengan_nominal_kosong()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);

        // Data pembayaran piutang yang invalid (nominal kosong)
        $pembayaranData = [
            'tanggal_pembayaran' => now()->toDateString(), // Mengisi tanggal pembayaran dengan hari ini
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank', // Cara pembayaran valid
            'jumlah' => '', // Nominal dikosongkan
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan data pembayaran
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa ada error pada field 'jumlah' karena kosong
        $response->assertSessionHasErrors(['jumlah']);

        // Memastikan bahwa data pembayaran tidak disimpan ke dalam tabel 'bayar_piutangs'
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $pembayaranData['tanggal_pembayaran'],
            'pembayaran' => $pembayaranData['pembayaran'],
            'jumlah' => $pembayaranData['jumlah'],
            'id_usaha' => $user->id_usaha,
        ]);
    }

    public function test_menambahkan_data_pembayaran_piutang_valid_dengan_jumlah_angsuran_2_kali()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan dengan jumlah piutang yang bisa dibagi 2 angsuran
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 1000000,  // Total piutang yang harus dibayar
            'jumlah_cicilan' => 2,
            'sisa_piutang' => 1000000,    // Sisa piutang yang harus dibayar
            'status' => false,
        ]);

        // Pembayaran pertama, misalnya 500000
        $pembayaranData1 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 500000,  // Angsuran pertama
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran pertama
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData1);

        // Memastikan bahwa pembayaran pertama berhasil
        $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'jumlah' => $pembayaranData1['jumlah'],
        ]);

        // Memastikan bahwa sisa piutang berkurang setelah pembayaran pertama
        $piutang->refresh();
        $this->assertEquals(500000, $piutang->sisa_piutang); // Sisa piutang setelah pembayaran pertama

        // Pembayaran kedua, misalnya 500000 (sekarang diatur pada hari ini atau sebelum hari ini)
        $pembayaranData2 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),  // Pastikan pembayaran kedua tetap pada hari ini
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 500000,  // Angsuran kedua
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran kedua
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData2);

        // Memastikan bahwa pembayaran kedua berhasil
        $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'jumlah' => $pembayaranData2['jumlah'],
        ]);

        // Memastikan bahwa piutang lunas setelah pembayaran kedua
        $piutang->refresh();
        $this->assertEquals(0, $piutang->sisa_piutang);  // Memastikan sisa piutang menjadi 0

        // Periksa apakah status piutang berubah menjadi lunas
        $this->assertEquals(1, $piutang->status);  // Status piutang harus 1 (lunas)
    }

    public function test_menambahkan_data_pembayaran_piutang_valid_dengan_jumlah_angsuran_1dari2_lunas()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat data piutang yang akan dibayarkan dengan jumlah piutang yang bisa dibagi 2 angsuran
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 1000000,  // Total piutang yang harus dibayar
            'jumlah_cicilan' => 2,
            'sisa_piutang' => 1000000,    // Sisa piutang yang harus dibayar
            'status' => false,
        ]);

        // Pembayaran pertama, langsung dilunasi
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 1000000,  // Angsuran pertama dan langsung dilunasi
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk pembayaran pertama yang langsung dilunasi
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan bahwa pembayaran berhasil dan dialihkan ke halaman piutang
        $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'jumlah' => $pembayaranData['jumlah'],
        ]);

        // Memastikan bahwa sisa piutang menjadi 0 setelah pembayaran pertama
        $piutang->refresh();
        $this->assertEquals(0, $piutang->sisa_piutang); // Sisa piutang harus 0 setelah pembayaran

        // Memastikan bahwa status piutang berubah menjadi lunas (1)
        $this->assertEquals(1, $piutang->status);  // Status piutang harus 1 (lunas)
    }
    public function test_menambahkan_data_pembayaran_piutang_dengan_jumlah_kurang_dari_sisa_piutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);
    
        // Membuat data piutang dengan jumlah piutang yang bisa dibagi 2 angsuran
        $piutang = Piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 1000000,  // Total piutang yang harus dibayar
            'jumlah_cicilan' => 2,
            'sisa_piutang' => 1000000,    // Sisa piutang yang harus dibayar
            'status' => false,
        ]);
    
        // Pembayaran pertama, misalnya 500000
        $pembayaranData1 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 500000,  // Angsuran pertama
            'id' => $piutang->id,
        ];
    
        // Mengirimkan permintaan POST untuk pembayaran pertama
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData1);
    
        // Memastikan bahwa pembayaran pertama berhasil
        $response->assertRedirect(route('piutang.index'));
        $this->assertDatabaseHas('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'jumlah' => $pembayaranData1['jumlah'],
        ]);
    
        // Memastikan bahwa sisa piutang berkurang setelah pembayaran pertama
        $piutang->refresh();
        $this->assertEquals(500000, $piutang->sisa_piutang); // Sisa piutang setelah pembayaran pertama
    
        // Pembayaran kedua dengan jumlah kurang dari sisa piutang
        $pembayaranData2 = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,  // Jumlah kurang dari sisa piutang
            'id' => $piutang->id,
        ];
    
        // Mengirimkan permintaan POST untuk pembayaran kedua
        $response = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData2);
    
        // Memastikan bahwa pesan error muncul karena jumlah pembayaran kurang
        $response->assertSessionHasErrors(['jumlah' => 'Kesalahan: Pembayaran yang dimasukkan tidak mencukupi jumlah angsuran yang ditentukan.']);
    
        // Memastikan bahwa data pembayaran tidak disimpan di database
        $this->assertDatabaseMissing('bayar_piutangs', [
            'id_piutang' => $piutang->id,
            'jumlah' => $pembayaranData2['jumlah'],
        ]);
    
        // Memastikan sisa piutang tidak berubah setelah pembayaran gagal
        $piutang->refresh();
        $this->assertEquals(500000, $piutang->sisa_piutang);  // Sisa piutang tetap sama karena pembayaran gagal
    }
    
}    