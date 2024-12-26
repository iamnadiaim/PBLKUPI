<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    public function test_view_register()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.signup');

    }

    public function test_register_with_valid_input()
    {
        // Simulasi request POST ke endpoint register
        $response = $this->post('/register', [
            'nama' => 'nadia intan',
            'alamat' => 'tukang kayu',
            'nama_usaha' => 'tahu',
            'no_telepon' => '081234567891',
            'email' => 'iamnadia@gmail.com',
            'password' => '1234567890',
        ]);

        // Cek apakah session tidak memiliki error
        $response->assertSessionHasNoErrors();

        // Cek apakah pengguna berhasil diarahkan (misalnya ke dashboard)
        $response->assertRedirect('/dashboard');

        // Pastikan user baru ditambahkan ke database
        $this->assertDatabaseHas('users', [
            'email' => 'iamnadia@gmail.com',
        ]);
    }

    /**
     * Test jika ada field yang tidak valid, maka akan muncul error.
     */
    public function test_register_with_invalid_input()
    {
        $response = $this->post('/register', [
            'nama' => '', // Nama kosong
            'alamat' => 'tukang kayu',
            'nama_usaha' => 'tahu',
            'no_telepon' => '081234567891', 
            'email' => 'iamnadiagmail.com', // Format email salah
            'password' => '1234567890',
        ]);

        // Pastikan ada error untuk field nama dan email
        $response->assertSessionHasErrors(['nama', 'email']);
    }

    /**
     * Test jika email sudah terdaftar, maka akan muncul error.
     */
    public function test_email()
    {
        // Buat user dengan email yang akan kita coba daftarkan lagi
        User::factory()->create([
            'email' => 'iamnadia@gmail.com',
        ]);

        // Coba daftar dengan email yang sudah ada
        $response = $this->post('/register', [
            'nama' => 'nadia intan',
            'alamat' => 'tukang kayu',
            'nama_usaha' => 'tahu',
            'no_telepon' => '081234567891',
            'email' => 'iamnadia@gmail.com', // Email sudah ada
            'password' => '1234567890',
        ]);

        // Pastikan ada error pada email
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test jika no telepon kurang dari 12 digit atau lebih dari 13 digit.
     */
    public function test_register_with_invalid_phone_number()
    {
        $response = $this->post('/register', [
            'nama' => 'nadia intan',
            'alamat' => 'tukang kayu',
            'nama_usaha' => 'tahu',
            'no_telepon' => '0812', // No telepon kurang dari 12 digit
            'email' => 'iamnadia@gmail.com',
            'password' => '1234567890',
        ]);

        // Pastikan ada error pada field no_telepon
        $response->assertSessionHasErrors(['no_telepon']);
    }
}
