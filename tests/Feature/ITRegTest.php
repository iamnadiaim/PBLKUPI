<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\login;
use App\Models\Usaha;
use App\Models\User;

class ITRegTest extends TestCase
{
    public function test_view_register()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.signup');

    }

    public function test_view_add_account()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.signup');
        
        $data = [
            'nama' => 'nadia intan',
            'alamat' => 'tukang kayu',
            'nama_usaha' => 'tahu',
            'no_telepon' => '081234567891',
            'email' => 'iamnadia@gmail.com',
            'password' => '1234567890',
        ];

        $response = $this->post(route('register'), $data);

         // Memastikan data tersimpan di database
        $this->assertDatabaseHas('users', [
            'nama' => 'nadia intan',
            'no_telepon' => '081234567891',
            'email' => 'iamnadia@gmail.com',
        ]);

        // Memastikan redirect ke halaman login
        $response->assertRedirect(route('login'));
    }

}
