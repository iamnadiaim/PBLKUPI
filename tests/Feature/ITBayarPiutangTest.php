<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\piutang;

class ITBayarPiutangTest extends TestCase
{
    public function test_menampilkan_halaman_piutang_index()
    {
        // Setup: Membuat user
        $user = User::factory()->create();

        // Login sebagai user
        $this->actingAs($user);

        // Akses halaman index
        $response = $this->get(route('piutang.index'));

        // Assertions
        $response->assertStatus(200); // Pastikan halaman dapat diakses
        $response->assertViewIs('piutang.index'); // Pastikan view yang digunakan benar
    }

    public function test_menampilkan_halaman_piutang_index_dan_halaman_pembayaran_hutang()
    {
        // Setup: Membuat user
        $user = User::factory()->create([
            'id_usaha' => 1, // Pastikan id_usaha sesuai
        ]);

        // Login sebagai user
        $this->actingAs($user);

        // Test 1: Menampilkan halaman piutang index
        $responsePiutang = $this->get(route('piutang.index'));
        $responsePiutang->assertStatus(200); // Pastikan halaman dapat diakses
        $responsePiutang->assertViewIs('piutang.index'); // Pastikan view yang digunakan benar

        // Test 2: Menampilkan halaman pembayaran hutang
        // Membuat data hutang untuk user yang sama
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
        ]);

        $responseBayarHutang = $this->get(route('bayarpiutang.create', ['id' => $piutang->id]));
        $responseBayarHutang->assertStatus(200); // Pastikan halaman dapat diakses
        $responseBayarHutang->assertViewIs('pembayaran.piutang'); // Pastikan view yang digunakan benar
    }
    
    /** @test */
    public function test_menampilkan_halaman_piutang_index_dan_pembayaran_piutang_dan_menambah_pembayaran_piutang()
    {
        // Setup: Membuat user
        $user = User::factory()->create([
            'id_usaha' => 1, // Pastikan id_usaha sesuai
        ]);
        // Login sebagai user
        $this->actingAs($user);

        // Menampilkan halaman piutang index 
        $responsePiutang = $this->get(route('piutang.index'));
        $responsePiutang->assertStatus(200); // Pastikan halaman dapat diakses
        $responsePiutang->assertViewIs('piutang.index'); // Pastikan view yang digunakan benar

        // Membuat data piutang untuk user ini
        $piutang = piutang::factory()->create([
            'id_usaha' => $user->id_usaha,
            'jumlah_piutang' => 500000,
            'sisa_piutang' => 500000,
            'status' => false,
        ]);
        $responseBayarPiutang = $this->get(route('bayarpiutang.create', ['id' => $piutang->id]));
        $responseBayarPiutang->assertStatus(200); // Pastikan halaman dapat diakses
        $responseBayarPiutang->assertViewIs('pembayaran.piutang'); // Pastikan view yang digunakan benar
        // Data pembayaran piutang
        $pembayaranData = [
            'tanggal_pembayaran' => now()->format('Y-m-d'),
            'nama' => $piutang->nama,
            'pembayaran' => 'Transfer Bank',
            'jumlah' => 200000,
            'id' => $piutang->id,
        ];

        // Mengirimkan permintaan POST untuk menyimpan pembayaran
        $responseSimpan = $this->post(route('bayarpiutang.store', ['id' => $piutang->id]), $pembayaranData);

        // Memastikan data pembayaran tersimpan di database
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

        // Memastikan bahwa sisa piutang diupdate dengan benar
        $piutang->refresh();
        $this->assertEquals($sisaPiutangExpected, $piutang->sisa_piutang);

        // Memastikan pengguna diarahkan ke halaman index dengan pesan sukses
        $responseSimpan->assertRedirect(route('piutang.index'));
        $responseSimpan->assertSessionHas('success', 'Pembayaran piutang berhasil disimpan.');
    }
}