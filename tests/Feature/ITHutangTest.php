<?php

namespace Tests\Feature;

use App\Models\hutang;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;

class ITHutangTest extends TestCase
{
    /** @test */
    public function menampilkan_halaman_hutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Mengakses halaman daftar hutang tanpa ada data hutang
        $response = $this->get(route('hutang.index'));

        // Memastikan halaman dapat diakses
        $response->assertStatus(200);
    }
    /** @test */
    public function menampilkan_hutang_dan_menambah_hutang()
    {
        // Membuat pengguna dan mengautentikasi mereka
        $user = User::factory()->create([
            'id_usaha' => 1,
        ]);
        $this->actingAs($user);

        // Membuat beberapa data hutang untuk pengguna ini
        hutang::factory()->count(3)->create([
            'id_usaha' => $user->id_usaha,
        ]);

        // Mengakses halaman daftar hutang
        $response = $this->get(route('hutang.index'));

        // Memastikan halaman dapat diakses
        $response->assertStatus(200);

        // Memastikan data hutang ditampilkan di halaman
        $hutangs = hutang::where('id_usaha', $user->id_usaha)->get();
        foreach ($hutangs as $hutang) {
            $response->assertSee($hutang->nama);
            $response->assertSee($hutang->jumlah_hutang);
            $response->assertSee($hutang->jumlah_cicilan);
            $response->assertSee($hutang->catatan);
            $response->assertSee(Carbon::parse($hutang->tanggal_pinjaman)->format('Y-m-d'));
            $response->assertSee(Carbon::parse($hutang->tanggal_jatuh_tempo)->format('Y-m-d'));
            $response->assertSee($hutang->sisa_hutang);
            $response->assertSee($hutang->status);
        }
    }
}
