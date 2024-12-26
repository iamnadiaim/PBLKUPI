<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BayarHutang;
use App\Models\hutang;
use App\Models\Usaha;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;


class ITBayarHutangTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();

        $this->artisan('migrate:fresh --seed');

        $this->post(route('login'), [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);
    }

    public function test_view_hutang()
    {
        $response = $this->get('/hutang');
        $response->assertStatus(200);
        $response->assertViewIs('hutang.index');
    }

    public function test_view_hutang_view_bayar_hutang()
    {
        $response = $this->get('/hutang');
        $response->assertStatus(200);
        $response->assertViewIs('hutang.index');

        $hutang = Hutang::create([
            'id_usaha' => 1, // Hubungkan dengan usaha user
            'tanggal_pinjaman' => now(),
            'tanggal_jatuh_tempo' => Carbon::create(2024, 11, 8),
            'nama' => 'ika',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'catatan' => 'Hutang untuk pembelian barang',
            'sisa_hutang' => 50000
        ]);
        $this->assertDatabaseHas('hutangs', [
            'id_usaha' => 1, // Hubungkan dengan usaha user
            'tanggal_pinjaman' => now(),
            'tanggal_jatuh_tempo' => Carbon::create(2024, 11, 8),
            'nama' => 'ika',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'catatan' => 'Hutang untuk pembelian barang',
            'sisa_hutang' => 50000
        ]);

        
        $response = $this->get(route('bayarhutang.create', ['id' => $hutang->id]));
        $response->assertStatus(200);

        // Memastikan halaman memuat data hutang yang benar
        $response->assertViewIs('pembayaran.hutang');
        $response->assertViewHas('hutang', function ($viewHutang) use ($hutang) {
            return $viewHutang->id === $hutang->id && $viewHutang->jumlah === $hutang->jumlah;
        });
    }

    public function test_all_view_add_bayar_hutang()
    {
        $response = $this->get('/hutang');
        $response->assertStatus(200);
        $response->assertViewIs('hutang.index');

        $hutang = Hutang::create([
            'id_usaha' => 1, 
            'tanggal_pinjaman' => now(),
            'tanggal_jatuh_tempo' => Carbon::create(2024, 11, 18),
            'nama' => 'ika',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'catatan' => 'Hutang untuk pembelian barang',
            'sisa_hutang' => 50000
        ]);
        $this->assertDatabaseHas('hutangs', [
            'id_usaha' => 1, // Hubungkan dengan usaha user
            'tanggal_pinjaman' => now(),
            'tanggal_jatuh_tempo' => Carbon::create(2024, 11, 18),
            'nama' => 'ika',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'catatan' => 'Hutang untuk pembelian barang',
            'sisa_hutang' => 50000
        ]);

        
        $response = $this->get(route('bayarhutang.create', ['id' => $hutang->id]));
        $response->assertStatus(200);

        $response->assertViewIs('pembayaran.hutang');
        $response->assertViewHas('hutang', function ($viewHutang) use ($hutang) {
            return $viewHutang->id === $hutang->id && $viewHutang->jumlah === $hutang->jumlah;
        });

        $response = $this->post(route('bayarhutang.store', ['id' => $hutang->id]), [
            'tanggal_pembayaran' => Carbon::create(2024, 11, 16),
            'nama' => 'ika',
            'pembayaran' => 'cash',
            'jumlah' => 50000
        ]);

        $this->assertDatabaseHas('bayar_hutangs', [
            'id_usaha' => 1,
            'tanggal_pembayaran' => Carbon::create(2024, 11, 16),
            'nama' => 'ika',
            'pembayaran' => 'cash',
            'jumlah' => 50000,
            'id_hutang' => $hutang->id
        ]);
        

        // Refresh object untuk mendapatkan data terbaru
        $hutang->refresh();

        // Periksa bahwa sisa hutang diperbarui menjadi 0
        $this->assertEquals(0, $hutang->sisa_hutang);

        // Pastikan redirect setelah pembayaran
        $response->assertRedirect(route('hutang.index'));
        
    }
}