<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Beban;
use Tests\TestCase;

class ITPengeluaranTest extends TestCase
{
    /**
     * Test berhasil menampilkan halaman form tambah pengeluaran.
     */
    public function test_menampilkan_halaman_form_tambah_pengeluaran()
    {
         // Setup: Membuat user
         $user = User::factory()->create([
            'id_usaha' => 1, // Pastikan id_usaha sesuai
        ]);

        // Login sebagai user
        $this->actingAs($user);
        $response = $this->get(route('beban.index')); // Pastikan route sesuai dengan aplikasi.
        $response->assertStatus(200);
        $response->assertViewIs('beban.index'); // Pastikan view sesuai dengan aplikasi.
    }

    /**
     * Test menambahkan kategori baru dan memverifikasi tampilannya.
     */
    public function test_menambahkan_kategori_dan_menampilkannya()
    {
         // Setup: Membuat user
         $user = User::factory()->create([
            'id_usaha' => 1, // Pastikan id_usaha sesuai
        ]);

        // Login sebagai user
        $this->actingAs($user);

        // Simulasi data kategori baru
        $kategoriData = [
            'nama' => 'Kategori Baru',
            'id_usaha' => $user->usaha->id, // Relasi `user` ke `usaha`.
        ];

        // Kirim request untuk menambahkan kategori
        $response = $this->post(route('kategori.store'), $kategoriData);
        $response->assertRedirect(); // Pastikan redirect berhasil

        // Verifikasi kategori tersimpan di database
        $this->assertDatabaseHas('kategoris', [
            'nama' => 'Kategori Baru',
            'id_usaha' => $user->usaha->id,
        ]);

        // Verifikasi kategori ditampilkan di halaman pengeluaran
        $response = $this->get(route('beban.index'));
        $response->assertOk();
        $response->assertSee('Kategori Baru');
    }

    /**
     * Test pengguna menambahkan kategori dan pengeluaran.
     */
    public function test_user_dapat_menambahkan_kategori_dan_pengeluaran()
    {
        // Setup: Membuat user
        $user = User::factory()->create([
            'id_usaha' => 1, // Pastikan id_usaha sesuai
        ]);

        // Login sebagai user
        $this->actingAs($user);
        // Tambahkan kategori
        $kategoriData = [
            'nama' => 'Kategori Baru',
            'id_usaha' => $user->usaha->id,
        ];
        $kategoriResponse = $this->post(route('kategori.store'), $kategoriData);
        $kategoriResponse->assertRedirect();
        $this->assertDatabaseHas('kategoris', $kategoriData);

        // Ambil kategori untuk pengeluaran
        $kategori = Kategori::where('nama', 'Kategori Baru')->first();

        // Simulasi data pengeluaran
        $bebanData = [
            'tanggal' => now()->format('Y-m-d'),
            'nama' => 'Pengeluaran Baru',
            'id_kategori' => $kategori->id,
            'jumlah' => 3,
            'harga' => 15000,
        ];

        // Tambah pengeluaran
        $bebanResponse = $this->post(route('beban.store'), $bebanData);
        $bebanResponse->assertRedirect(route('riwayatbeban'));
        $bebanResponse->assertSessionHas('success', 'pengeluaran berhasil ditambahkan');

        // Verifikasi pengeluaran tersimpan di database
        $this->assertDatabaseHas('bebans', array_merge($bebanData, ['id_usaha' => $user->usaha->id]));
    }
}
