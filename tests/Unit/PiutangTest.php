<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\piutang;
use Carbon\Carbon;

class PiutangTest extends TestCase
{
    public function test_menampilkan_halaman_daftar_piutang()
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
    
        // Mengirimkan permintaan GET ke route piutang.index
        $response = $this->get(route('piutang.index'));
    
        // Memastikan respons memiliki status 200 (OK)
        $response->assertStatus(200);
    
        // Memastikan bahwa data piutangs diteruskan ke tampilan
        $response->assertViewHas('piutangs');
    
        // Memastikan bahwa data yang benar ada di dalam tampilan
        $piutangs = piutang::where('id_usaha', $user->id_usaha)->get();
        $response->assertViewHas('piutangs', $piutangs);
    }

    public function test_menampilkan_halaman_tambah_piutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create();
        $this->actingAs($user);
    
        // Mengirimkan permintaan GET ke route piutang.create
        $response = $this->get(route('piutang.create'));
    
        // Memastikan respons memiliki status 200 (OK)
        $response->assertStatus(200);
    
        // Memastikan bahwa tampilan piutang.create ditampilkan
        $response->assertViewIs('piutang.create');
    }


    /** @test */
    public function test_menambah_piutang_data_valid_dan_mengarahkan_dengan_pesan_sukses()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Data untuk piutang baru yang valid
        $piutangData = [
            'nama' => 'Piutang Test',
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '2025-11-12',
        ];
        // Mengirimkan permintaan POST dengan data valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan bahwa data piutang disimpan di database
        $this->assertDatabaseHas('piutangs', array_merge($piutangData, [
            'id_usaha' => $user->id_usaha,
            'sisa_piutang' => $piutangData['jumlah_piutang'],
            'status' => false,
        ]));
        // Memastikan pengguna diarahkan kembali ke route piutang.index
        $response->assertRedirect(route('piutang.index'));
        // Memastikan pesan sukses ditampilkan di sesi
        $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');
    }

    /** @test */
    public function test_validasi_tanggal_pinjaman_hari_kemarin_berhasil() 
    {
        $piutangData = [
            'nama' => 'Piutang Test kemarin',
            'catatan' => 'Test Note kemarin',
            'jumlah_piutang' => 400000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->subDay()->toDateString(), // tanggal kemarin
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
            // Data lainnya
        ];

        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi tidak gagal pada tanggal pinjaman
        $response->assertSessionDoesntHaveErrors(['tanggal_pinjaman']);
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_tanggal_pinjaman_hari_ini()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan tanggal pinjaman hari ini
        $piutangDataHariIni = [
            'nama' => 'Piutang Test Hari Ini',
            'catatan' => 'Test Note Hari Ini',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'), // Hari ini
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data valid untuk hari ini
        $responseHariIni = $this->post(route('piutang.store'), $piutangDataHariIni);

        // Memastikan data piutang hari ini disimpan di database
        $this->assertDatabaseHas('piutangs', array_merge($piutangDataHariIni, [
            'id_usaha' => $user->id_usaha,
            'sisa_piutang' => $piutangDataHariIni['jumlah_piutang'],
            'status' => false,
        ]));

        // Memastikan pengguna diarahkan kembali ke route piutang.index
        $responseHariIni->assertRedirect(route('piutang.index'));
        $responseHariIni->assertSessionHas('success', 'Piutang berhasil ditambahkan.');
    }
    /** @test */
    public function test_tidak_bisa_menambahkan_data_piutang_dengan_tanggal_pinjaman_setelah_hari_ini()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan tanggal pinjaman setelah hari ini
        $piutangDataTanggalSetelahHariIni = [
            'nama' => 'Piutang Test Setelah Hari Ini',
            'catatan' => 'Test Note Setelah Hari Ini',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->addDays()->format('Y-m-d'), // Tanggal setelah hari ini
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data tidak valid (tanggal setelah Hari Ini)
        $response = $this->post(route('piutang.store'), $piutangDataTanggalSetelahHariIni);

        // Memastikan bahwa data piutang tidak tersimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'Piutang Test Setelah Hari Ini',
            'id_usaha' => $user->id_usaha,
        ]);

        // Memastikan bahwa respons memiliki error untuk tanggal_pinjaman
        $response->assertSessionHasErrors(['tanggal_pinjaman' ]);
    }

    /** @test */
    public function test_validasi_tanggal_jatuh_tempo_hari_kemarin()
    {
        $this->actingAs(User::factory()->create());

        // Data yang dikirimkan
        $piutangData = [
            'nama' => 'Piutang kemarin',
            'catatan' => 'Test kemarin',
            'jumlah_piutang' => 70000,
            'jumlah_cicilan' => 10,
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'), // Tanggal pinjaman hari ini
            'tanggal_jatuh_tempo' => Carbon::now()->subDay(2)->format('Y-m-d'), // Tanggal jatuh tempo kemarin
        ];

        // Kirim request POST
    $response = $this->post(route('piutang.store'), $piutangData);

    // Pastikan ada error pada 'tanggal_jatuh_tempo'
    $response->assertSessionHasErrors(['tanggal_jatuh_tempo']);
    }

    /** @test */
    public function test_validasi_tanggal_jatuh_tempo_hari_ini_berhasil()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $piutangData = [
            'nama' => 'Piutang today',
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000, 
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'), // Hari ini
            'tanggal_jatuh_tempo' => now()->format('Y-m-d'),
        ];

        $response = $this->post(route('piutang.store'), $piutangData);

        $this->assertDatabaseHas('piutangs', array_merge($piutangData, [
            'id_usaha' => $user->id_usaha,
            'sisa_piutang' => $piutangData['jumlah_piutang'],
            'status' => false,
        ]));

        $response->assertRedirect(route('piutang.index'));
        $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');
    }
    /** @test */
    public function test_validasi_tanggal_jatuh_tempo_setelah_hari_ini()
    {
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        $piutangData = [
            'nama' => 'Piutang tust',
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000, 
            'jumlah_cicilan' => 12,
            // Mengatur tanggal pinjaman sebagai hari ini
            'tanggal_pinjaman' => now()->startOfDay()->format('Y-m-d'),
            // Mengatur tanggal jatuh tempo satu hari setelah hari ini
            'tanggal_jatuh_tempo' => now()->addDay()->startOfDay()->format('Y-m-d'),
        ];

        $response = $this->post(route('piutang.store'), $piutangData);

        $this->assertDatabaseHas('piutangs', array_merge($piutangData, [
            'id_usaha' => $user->id_usaha,
            'sisa_piutang' => $piutangData['jumlah_piutang'],
            'status' => false,
        ]));

        $response->assertRedirect(route('piutang.index'));
        $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_nama_kombinasi_huruf_dan_angka()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan nama kombinasi huruf dan angka
        $piutangData = [
            'nama' => 'Piutang123Test', // Nama dengan huruf dan angka
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan data piutang disimpan di database
        $this->assertDatabaseHas('piutangs', array_merge($piutangData, [
            'id_usaha' => $user->id_usaha,
            'sisa_piutang' => $piutangData['jumlah_piutang'],
            'status' => false,
        ]));

        // Memastikan pengguna diarahkan kembali ke route piutang.index
        $response->assertRedirect(route('piutang.index'));
        $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_nama_customer_kurang_dari_2_karakter()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan nama customer kurang dari 2 karakter
        $piutangData = [
            'nama' => 'A', // Nama customer kurang dari 2 karakter
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada field 'nama'
        $response->assertSessionHasErrors(['nama' => 'Nama harus memiliki minimal 2 karakter.']);

        // Memastikan data tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'A',
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_nama_customer_lebih_dari_60_karakter()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan nama customer lebih dari 60 karakter
        $piutangData = [
            'nama' => str_repeat('A', 61), // Nama customer lebih dari 60 karakter
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada field 'nama'
        $response->assertSessionHasErrors(['nama' => 'Nama tidak boleh lebih dari 60 karakter.']);

        // Memastikan data tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => str_repeat('A', 61),
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_nama_customer_yang_sudah_ada()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);
        // Membuat data piutang dengan nama customer tertentu
        $existingPiutang = piutang::factory()->create([
            'nama' => 'Customer Sama',
            'id_usaha' => $user->id_usaha,
        ]);
        // Data piutang baru dengan nama customer yang sama
        $piutangData = [
            'nama' => 'Customer Sama', // Nama customer yang sama
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);
        // Memastikan validasi gagal pada field 'nama'
        $response->assertSessionHasErrors(['nama' => 'Nama Customer telah digunakan. Silakan gunakan nama lain.']);
        // Memastikan data baru tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'Customer Sama',
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
        ]);
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_nominal_kurang_dari_minimal()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan jumlah_piutang kurang dari 1000
        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test Note',
            'jumlah_piutang' => 500, // Nominal kurang dari 1000
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada field 'jumlah_piutang'
        $response->assertSessionHasErrors(['jumlah_piutang' => 'Nominal tidak boleh kurang dari 1000.']);

        // Memastikan data tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'Customer Test',
            'jumlah_piutang' => 500,
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_memasukkan_data_piutang_dengan_nominal_kombinasi_huruf()
    {
        $this->actingAs(User::factory()->create());

        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test kombinasi huruf dan angka',
            'jumlah_piutang' => '70000abcde', // Menggunakan kombinasi huruf dan angka
            'jumlah_cicilan' => 10,
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        // Kirim permintaan POST
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada jumlah_piutang dan ada error
        $response->assertSessionHasErrors('jumlah_piutang');

    }

    /** @test */
    public function test_memasukkan_data_piutang_dengan_jumlahcicilan_kombinasi_huruf()
    {
        $this->actingAs(User::factory()->create());

        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test kombinasi huruf dan angka',
            'jumlah_piutang' => '70000', // Menggunakan kombinasi huruf dan angka
            'jumlah_cicilan' => '10abcde',
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        // Kirim permintaan POST
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada jumlah_piutang dan ada error
        $response->assertSessionHasErrors('jumlah_cicilan');

    }


    /** @test */
    public function test_memasukkan_jumlah_cicilan_kurang_dari_batas_minimum()
    {
        $this->actingAs(User::factory()->create());
        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test jumlah cicilan kurang dari batas minimum',
            'jumlah_piutang' => 100000,
            'jumlah_cicilan' => 0,  // Jumlah cicilan kurang dari 1
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        // Kirim permintaan POST
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada jumlah_cicilan dan ada error
        $response->assertSessionHasErrors('jumlah_cicilan');  // Memastikan error muncul pada jumlah_cicilan
    }

    /** @test */
    public function test_memasukkan_jumlah_cicilan_lebih_dari_batas_maksimum()
    {
        $this->actingAs(User::factory()->create());
        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test jumlah cicilan lebih dari batas maksimum',
            'jumlah_piutang' => 100000,
            'jumlah_cicilan' => 37,  // Jumlah cicilan lebih dari 36
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        // Kirim permintaan POST
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada jumlah_cicilan dan ada error
        $response->assertSessionHasErrors('jumlah_cicilan');  // Memastikan error muncul pada jumlah_cicilan
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_catatan_kurang_dari_5_karakter()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan catatan kurang dari 5 karakter
        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => 'Test', // Catatan kurang dari 5 karakter
            'jumlah_piutang' => 5000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada field 'catatan'
        $response->assertSessionHasErrors(['catatan' => 'Catatan harus memiliki minimal 5 karakter.']);

        // Memastikan data tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'Customer Test',
            'catatan' => 'Test', // Catatan dengan panjang kurang dari 5 karakter tidak valid
            'id_usaha' => $user->id_usaha,
        ]);
    }

    /** @test */
    public function test_menambahkan_data_piutang_dengan_catatan_lebih_dari_60_karakter()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create(['id_usaha' => 1]);
        $this->actingAs($user);

        // Data piutang dengan catatan lebih dari 60 karakter
        $piutangData = [
            'nama' => 'Customer Test',
            'catatan' => str_repeat('A', 61), // Catatan lebih dari 60 karakter
            'jumlah_piutang' => 5000,
            'jumlah_cicilan' => 12,
            'tanggal_pinjaman' => now()->format('Y-m-d'),
            'tanggal_jatuh_tempo' => now()->addMonth()->format('Y-m-d'),
        ];

        // Mengirimkan permintaan POST dengan data yang tidak valid
        $response = $this->post(route('piutang.store'), $piutangData);

        // Memastikan validasi gagal pada field 'catatan'
        $response->assertSessionHasErrors(['catatan' => 'Catatan tidak boleh lebih dari 60 karakter.']);

        // Memastikan data tidak disimpan di database
        $this->assertDatabaseMissing('piutangs', [
            'nama' => 'Customer Test',
            'catatan' => str_repeat('A', 61), // Catatan dengan panjang lebih dari 60 karakter tidak valid
            'id_usaha' => $user->id_usaha,
        ]);
    }

    public function test_validasi_field_nama_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => '',  // field kosong
            'catatan' => 'piutangnih',
            'jumlah_piutang' => '290000',
            'jumlah_cicilan' => '3',
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '2025-09-11',
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['nama']);
    }   

    public function test_validasi_field_catatan_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => 'posil',
            'catatan' => '',  // field kosong
            'jumlah_piutang' => '390000',
            'jumlah_cicilan' => '4',
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '2025-04-18',
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['catatan']);
    }

    public function test_validasi_field_jumlahpiutang_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => 'nisa',
            'catatan' => 'piutang',
            'jumlah_piutang' => '', // field kosong
            'jumlah_cicilan' => '4',
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '2025-03-12',
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['jumlah_piutang']);
    }

    public function test_validasi_field_jumlahcicilan_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => 'nini',
            'catatan' => 'catatan piutang',
            'jumlah_piutang' => '3000000',
            'jumlah_cicilan' => '',  // field kosong
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '2025-11-12',
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['jumlah_cicilan']);
    }












    public function test_validasi_field_tanggalpinjaman_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => 'nano',
            'catatan' => 'beli kue',
            'jumlah_piutang' => '100000',
            'jumlah_cicilan' => '5',
            'tanggal_pinjaman' => '', // field kosong
            'tanggal_jatuh_tempo' => '2024-11-30',
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['tanggal_pinjaman']);
    }

    public function test_validasi_field_tanggaljatuhtempo_wajib_diisi()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengirimkan permintaan POST dengan data yang tidak lengkap (invalid)
        $response = $this->post(route('piutang.store'), [
            'nama' => 'indah',
            'catatan' => 'pembelian bahan baku',
            'jumlah_piutang' => '200000',
            'jumlah_cicilan' => '2',
            'tanggal_pinjaman' => '2024-11-12',
            'tanggal_jatuh_tempo' => '', // field kosong
        ]);

        // Memastikan validasi gagal dan kembali ke form dengan error
        $response->assertSessionHasErrors(['tanggal_jatuh_tempo']);
    }
        
}