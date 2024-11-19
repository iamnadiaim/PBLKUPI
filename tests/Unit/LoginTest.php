<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    public function test_view_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');

    }

    public function test_login_succes_with_valid_data()
    {
        
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/dashboard');
    }
    
    public function test_login_fail_email_and_password_not_registered(){

        $response = $this->post('/login', [
            'email' => 'matahari@gmail.com',
            'password' => 'usaha234',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas("errorLogin", "Email Atau Password Salah");
    }
    
    public function test_if_email_salah()
    {
        // Pastikan tidak ada pengguna di database untuk menguji login
        $response = $this->post('/login', [
            'email' => 'matahari@gmail.com',
            'password' => 'admin123',
        ]);

        // Pastikan pengguna diarahkan kembali ke halaman login
        $response->assertRedirect('/');
        $response->assertSessionHas("errorLogin", "Email Atau Password Salah");

    }

    public function test_if_password_salah()
    {
        // Pastikan tidak ada pengguna di database untuk menguji login
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin',
        ]);

        // Pastikan pengguna diarahkan kembali ke halaman login
        $response->assertRedirect('/');
        $response->assertSessionHas("errorLogin", "Email Atau Password Salah");
    }

    public function test_login_fail_email_format_invalid_no_dot()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmailcom',
            'password' => 'admin',
        ]);
        $response->assertRedirect('/');
        $response->assertSessionHas("errorLogin", "Email Atau Password Salah");
    }
    public function test_login_fail_email_format_invalid()
    {
        $response = $this->post('/login', [
            'email' => 'admingmail.com',
            'password' => 'admin',
        ]);
            // dd($response->get());
        $response->assertRedirect('/');
        $response->assertSessionHasErrors([
            'email' => 'The email field must be a valid email address.',
        ]);    
    }
    public function test_validasi_form_all_field_unfilled()
    {
        // Kirimkan request login tanpa email dan password
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email', 'password']);
    }
    public function test_validasi_form_email_field_unfilled()
    {
        // Kirimkan request login tanpa email dan password
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'admin',
        ]);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['email']);
    }
    public function test_validasi_form_password_field_unfilled()
    {
        // Kirimkan request login tanpa email dan password
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '',
        ]);
        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['password']);
        $response->assertSessionHas("errorLogin", "Email Atau Password Salah");
    }
}
