<?php

namespace Tests\Feature;

use App\Models\Saldo;
use App\Models\User;
use Tests\TestCase;

class ArusKasTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first(); 
        $this->actingAs($this->user); 
    }

    /**
     * TC-ArusKas-01: Test memasukkan saldo awal usaha dengan data valid.
     */
    public function test_saldo_awal_valid()
    {
        $response = $this->post(route('saldo.store'), [
            'saldo' => 200000,
        ]);

        $response->assertStatus(302); // Redirect setelah input sukses
        $this->assertDatabaseHas('saldos', [
            'id_usaha' => $this->user->id_usaha,
            'saldo' => 200000,
        ]);
    }

    /**
     * TC-ArusKas-02: Test memasukkan saldo awal usaha dengan data invalid (kombinasi huruf).
     */
    public function test_saldo_awal_invalid_huruf()
    {
        $response = $this->post(route('saldo.store'), [
            'saldo' => '1000ABC' // kombinasi huruf dan angka
        ]);

        $response->assertSessionHasErrors(['saldo' => 'The saldo field must be a number.']);
        $this->assertDatabaseMissing('saldos', ['saldo' => '1000ABC']); 
    }

    /**
     * TC-ArusKas-03: Test memasukkan saldo awal dengan jumlah kurang dari 100.000.
     */
    public function test_saldo_kurang_dari_batas_minimum()
    {
        $response = $this->post(route('saldo.store'), [
            'saldo' => 50000 //kurang dari batas minimum
        ]);

        $response->assertSessionHasErrors(['saldo' => 'The saldo field must be at least 100000.']);
        $this->assertDatabaseMissing('saldos', ['saldo' => 50000]);
    }

    /**
     * TC-ArusKas-04: Test melihat halaman laporan aruskas bulan terkini.
     */
    public function test_view_halaman_arus_kas()
    {
        $response = $this->get(route('aruskas.index'));

        $response->assertStatus(200);
        $response->assertViewHas(['kategoris', 'cekSaldo', 'totalPendapatan', 'saldoAkhir']);
    }

    /**
     * TC-ArusKas-05: Test melihat laporan aruskas periode tertentu.
     */
    public function test_view_laporan_aruskas()
    {
        $response = $this->get(route('aruskas.index', ['month' => '2024-11']));

        $response->assertStatus(200);
        $response->assertViewHas(['kategoris', 'cekSaldo', 'totalPendapatan', 'saldoAkhir']);
    }

    /**
     * TC-ArusKas-06: Test mencetak laporan arus kas dalam format PDF.
     */
    public function test_cetak_laporan_arus_kas()
    {
        $response = $this->get(route('cetakaruskas', ['month' => '2024-11']));

        $response->assertStatus(200);
        $response->assertViewIs('cetakaruskas');
    }
}
