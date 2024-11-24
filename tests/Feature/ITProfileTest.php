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
        // view halaman profil
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
        // Mview halaman profil
        $response = $this->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Edit');

        // view halaman edit profil
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

    public function test_view_profile_view_edit_profile_dan_edit_profile()
    {
        // Mengakses halaman profile
        $response = $this->get(route('profile'));
        $response->assertStatus(200);

        // Mengakses halaman edit profile
        $response = $this->get(route('profileedit'));
        $response->assertStatus(200);

        // Simulasi file gambar menggunakan file teks sebagai pengganti
        $image = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        // Merubah data pada form edit
        $response = $this->put(route('editProfile'), [
            'nama' => 'Updated Name',
            'email' => 'updated.email@example.com',
            'no_telepon' => '081234567890',
            'nama_usaha' => 'Updated Business Name',
            'alamat' => 'Updated Address',
            'img_profile' => $image, 
        ]);

        // Memastikan redirect e halaman profile
        $response->assertRedirect(route('profile'));

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
