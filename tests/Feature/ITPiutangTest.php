<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Piutang;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ITPiutangTest extends TestCase
{
   // use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh --seed');

        $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);
    }

    // IT-Piutang-01: Pengujian tampilan Piutang
    public function test_View_Piutang()
{

    // Mengakses halaman piutang
    $response = $this->get(route('piutang.index'));
    $response->assertStatus(200);

    // Memastikan ada tabel yang berisi data piutang
    $response->assertSee('Tanggal Peminjaman');
    $response->assertSee('Nama Costumer');
    $response->assertSee('Nominal');
    $response->assertSee('Sisa Piutang');
}


    // IT-Piutang-02: Pengujian tambah piutang
    public function test_view_piutang_create_piutang()
{
    // view halaman "Create Piutang"
    $response = $this->get(route('piutang.create'));
    $response->assertStatus(200);

    // mengisi piutang data
    $response = $this->post(route('piutang.store'), [
        'tanggal_pinjaman' => '2024-11-24',
        'tanggal_jatuh_tempo' => '2025-11-24',
        'nama' => 'Customer Test',
        'jumlah_piutang' => 5000,
        'jumlah_cicilan' => 12,
        'catatan' => 'Test piutang',
    ]);

    // Memastikan redirect kembali ke halaman piutang
    $response->assertRedirect(route('piutang.index'));
    $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');

    //  Memverifikasi data baru berhasil ditampilkan
    $this->get(route('piutang.index'))
         ->assertSeeText('Customer Test')
         ->assertSeeText('5000')
         ->assertSeeText('12');
}
}
