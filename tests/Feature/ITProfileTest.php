<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ITProfileTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Migrasi ulang database dengan data seed
        $this->artisan('migrate:fresh --seed');

        // Login sebagai admin
        $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);
    }

    public function test_view_profile()
    {
        // Memastikan user sudah login dan halaman profil dapat diakses
        $response = $this->get(route('profile'));

        // Memastikan response sukses dan menampilkan halaman profil
        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertSee('Nama');
        $response->assertSee('Email');
        $response->assertSee('Nama Usaha');
        $response->assertSee('No Telepon');
        $response->assertSee('Alamat');
    }

    public function test_view_profile_view_edit_profile()
    {
        // Memastikan user sudah login dan dapat mengakses halaman profil
        $response = $this->get(route('profile'));

        // Memastikan halaman profil dapat diakses
        $response->assertStatus(200);
        $response->assertSee('Edit');

        // Klik tombol Edit untuk mengakses halaman edit profil
        $response = $this->get(route('profileedit'));

        // Memastikan halaman edit profil ditampilkan dengan benar
        $response->assertStatus(200);
        $response->assertViewIs('user.edit');
        $response->assertSee('Nama');
        $response->assertSee('Email');
        $response->assertSee('No Telepon');
        $response->assertSee('Nama Usaha');
        $response->assertSee('Alamat');
    }

    public function test_view_edit_profile_and_update_profile()
    {
        // Mengakses halaman profile
        $response = $this->get(route('profile'));
        $response->assertStatus(200);

        // Mengakses halaman edit profile
        $response = $this->get(route('profileedit'));
        $response->assertStatus(200);

        // Simulasi file gambar menggunakan file teks sebagai pengganti
        $image = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        // Melakukan perubahan data pada form edit
        $response = $this->put(route('editProfile'), [
            'nama' => 'Updated Name',
            'email' => 'updated.email@example.com',
            'no_telepon' => '081234567890',
            'nama_usaha' => 'Updated Business Name',
            'alamat' => 'Updated Address',
            'img_profile' => $image, // File valid tetapi bukan hasil GD
        ]);

        // Memastikan redirect kembali ke halaman profile
        $response->assertRedirect(route('profile'));

        // Memastikan pesan sukses muncul
        $response->assertSessionHas('success', 'Profile updated successfully.');

        // Memverifikasi data baru telah tersimpan
        $this->assertDatabaseHas('users', [
            'nama' => 'Updated Name',
            'email' => 'updated.email@example.com',
            'no_telepon' => '081234567890',
        ]);

        $this->assertDatabaseHas('usaha', [
            'nama_usaha' => 'Updated Business Name',
            'alamat' => 'Updated Address',
        ]);
    }
}
