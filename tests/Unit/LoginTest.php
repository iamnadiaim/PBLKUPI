<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{

    /**
     * Test login berhasil dengan kredensial yang benar.
     */
    public function test_with_validdata()
    {

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard');

    }

    /**
     * Test validasi form login.
     */
    public function test_validasi_form()
    {
        // Kirimkan request login tanpa email dan password
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        // Pastikan ada validasi error pada email dan password
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_view_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');

    }
    
    public function test_if_email_atau_password_salah()
    {
        // Pastikan tidak ada pengguna di database untuk menguji login
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ]);

        // Pastikan pengguna diarahkan kembali ke halaman login
        $response->assertRedirect('/');
    }
}
