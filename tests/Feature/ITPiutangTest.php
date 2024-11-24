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

    // IT-Piutang-01: Pengujian interface Piutang
    public function testViewPiutang()
{

    // Mengakses halaman piutang
    $response = $this->get(route('piutang.index'));

    // Memastikan halaman berhasil dimuat (status 200)
    $response->assertStatus(200);

    // Memastikan ada tabel yang berisi data piutang
    $response->assertSee('Tanggal Peminjaman');
    $response->assertSee('Nama Costumer');
    $response->assertSee('Nominal');
    $response->assertSee('Sisa Piutang');
}


    // IT-Piutang-02: Pengujian tambah piutang
    public function test_add_piutang()
{
    // Go to the "Create Piutang" page
    $response = $this->get(route('piutang.create'));
    $response->assertStatus(200);

    // Fill in the form with piutang data
    $response = $this->post(route('piutang.store'), [
        'tanggal_pinjaman' => '2024-11-24',
        'tanggal_jatuh_tempo' => '2025-11-24',
        'nama' => 'Customer Test',
        'jumlah_piutang' => 5000,
        'jumlah_cicilan' => 12,
        'catatan' => 'Test piutang addition',
    ]);

    // Assert the piutang was added successfully and redirected to the index page
    $response->assertRedirect(route('piutang.index'));
    $response->assertSessionHas('success', 'Piutang berhasil ditambahkan.');

    // Verify the new piutang appears in the list
    $this->get(route('piutang.index'))
         ->assertSeeText('Customer Test')
         ->assertSeeText('5000')
         ->assertSeeText('12');
}
}
