<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Hutang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Tests\Feature\Date;

class HutangTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first(); 
        $this->actingAs($this->user); 
    }

    // TC-Hutang-01: Admin melihat daftar hutang
    public function test_view_hutang()
    {
        $response = $this->get(route('hutang.index'));

        $response->assertStatus(200); 
        $response->assertViewIs('hutang.index'); 
        $response->assertViewHas('hutangs'); 
    }

    // TC-Hutang-02: Admin menambahkan data hutang dengan input valid
    public function test_hutang_valid()
    {
        $data = [
            'nama' => 'Sari',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertRedirect(route('hutang.index')); 
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('hutangs', $data); 
    }

    // TC-Hutang-03: Admin menambahkan data hutang tanpa tanggal jatuh tempo
    public function test_hutang_tanpa_tanggal_jatuh_tempo()
    {
        $data = [
            'nama' => 'Sari',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '',
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors('tanggal_jatuh_tempo');
    }

    // TC-Hutang-04: Admin menambahkan data hutang tanpa nama pemberi pinjaman
    public function test_hutang_tanpa_nama_pemberi_pinjam()
    {
        $data = [
            'nama' => '',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors('nama'); 
    }

    // TC-Hutang-05: Admin menambahkan data hutang tanpa nominal
    public function test_hutang_tanpa_nominal()
    {
        $data = [
            'nama' => 'Sari',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => '',
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ];

        $response = $this->post(route('hutang.store'), $data);

        $response->assertSessionHasErrors('jumlah_hutang'); 
    }

    // TC-Hutang-06: Hutang tanpa 'jumlah_cicilan'
    public function test__hutang_tanpa_cicilan()
    {
        $response = $this->post('/hutang', [
            'nama' => 'Sari',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => '',
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ]);

        $response->assertSessionHasErrors('jumlah_cicilan');
    }

    // TC-Hutang-07: Admin menambahkan hutang tanpa'catatan'
    public function test_hutang_tanpa_catatan()
    {
        $response = $this->post('/hutang', [
            'nama' => 'Sari',
            'catatan' => '',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ]);

        $response->assertSessionHasErrors('catatan');
    }

    // TC-Hutang-08: Admin menambahkan hutang dengan nama kurang dari batas minimum (3 karakter)
    public function test_hutang_nama_kurang_dari_batas_minimum()
    {
        $response = $this->post('/hutang', [
            'nama' => 's',  // 1 karakter
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ]);

        $response->assertSessionHasErrors('nama');
    }

    // TC-Hutang-09: Hutang dengan nama lebih dari bbatas maksimum (60 karakter)
    public function test_hutang_nama_lebih_dari_batas_maksimum()
    {
        $response = $this->post('/hutang', [
            'nama' => str_repeat('a', 61),  //61 karakter
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 6,
            'tanggal_pinjaman' => '2024-10-29',
            'tanggal_jatuh_tempo' => '2025-10-31',
        ]);

        $response->assertSessionHasErrors('nama');
    }

    // TC-Hutang-10: Admin menambahkan hutang dengan tangggal pinjaman untuk kemarin
    public function test_hutang_tanggal_peminjaman_kemarin()
    {
        $response = $this->post(route('hutang.store'), [
            'nama' => 'Nana',
            'catatan' => 'Nastar 500gr 1 bungkus',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'tanggal_pinjaman' => Carbon::yesterday()->format('Y-m-d'), // Tanggal kemarin
            'tanggal_jatuh_tempo' => Carbon::now()->format('Y-m-d'), // Tanggal hari ini
        ]);

        $response->assertRedirect(route('hutang.index'))
                 ->assertSessionHas('success', 'Hutang berhasil ditambahkan');
        
        // Cek database
        $this->assertDatabaseHas('hutangs', [
            'nama' => 'Nana',
            'tanggal_pinjaman' => Carbon::yesterday()->format('Y-m-d'),
        ]);
    }

    // TC-Hutang-11: hutang dengan tanggal pinjam untuk di masa depan
    public function test_hutang_tanggal_pinjaman_besok()
    {
        $response = $this->post(route('hutang.store'), [
            'nama' => 'Nana',
            'catatan' => 'Nastar 500gr 1 bungkus',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'tanggal_pinjaman' => Carbon::tomorrow()->format('Y-m-d'), // Tanggal besok
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(2)->format('Y-m-d'), // Tanggal setelahnya
        ]);

        $response->assertSessionHasErrors(['tanggal_pinjaman']);
    }

    // TC-Hutang-12: Admin menambahkan hutang dengan tanggal jatuh tempo hari ini
    public function test_hutang_tanggal_jatuh_tempo_today()
    {
        $response = $this->post(route('hutang.store'), [
            'nama' => 'Nana',
            'catatan' => 'Nastar 500gr 1 bungkus',
            'jumlah_hutang' => 50000,
            'jumlah_cicilan' => 1,
            'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'), // Tanggal hari ini
            'tanggal_jatuh_tempo' => Carbon::now()->format('Y-m-d'), // Tanggal hari ini
        ]);

        $response->assertRedirect(route('hutang.index'))
                 ->assertSessionHas('success', 'Hutang berhasil ditambahkan');
        
        // Cek database
        $this->assertDatabaseHas('hutangs', [
            'nama' => 'Nana',
            'tanggal_jatuh_tempo' => Carbon::now()->format('Y-m-d'),
        ]);
    }
  // TC-Hutang-13 : Admin menambahkan hutang dengan tanggal jatuh tempo di masa lalu 
  public function test_hutang_tanggal_jatuh_tempo_kemarin()
  {
    $response = $this->post(route('hutang.store'), [
        'nama' => 'Nana',
        'catatan' => 'Nastar 500gr 1 bungkus',
        'jumlah_hutang' => 50000,
        'jumlah_cicilan' => 1,
        'tanggal_pinjaman' => Carbon::now()->format('Y-m-d'), // Hari ini
        'tanggal_jatuh_tempo' => Carbon::yesterday()->format('Y-m-d'), // Kemarin
    ]);

    $response->assertSessionHasErrors(['tanggal_jatuh_tempo']);

  }

  // TC-Hutang-14: Admin menambahkan hutang dengan nominal kurang dari batas minimum (1000)
  public function test_hutang_nominal_kurang_dari_batas_minimum()
  {
      // Attempt to create a hutang with nominal less than 1000
      $response = $this->post(route('hutang.store'), [
          'nama' => 'Ira',
          'catatan' => 'ppppppppp',
          'jumlah_hutang' => 5,  // Nominal kurang dari 1000
          'jumlah_cicilan' => 1,
          'tanggal_pinjaman' => now()->format('Y-m-d'),
          'tanggal_jatuh_tempo' => now()->addDays(5)->format('Y-m-d'),
      ]);

      $response->assertSessionHasErrors('jumlah_hutang');
  }

  // Test Case: Admin menambahkan hutang dengan nominal lebih dari batas maksimum (10,000,000)
  public function test_hutang_nominal_lebih_dari_batas_maksimum()
  {
      $response = $this->post(route('hutang.store'), [
          'nama' => 'Ira',
          'catatan' => 'ppppppppp',
          'jumlah_hutang' => 11000000,  // Nominal lebih dari 10,000,000
          'jumlah_cicilan' => 1,
          'tanggal_pinjaman' => now()->format('Y-m-d'),
          'tanggal_jatuh_tempo' => now()->addDays(5)->format('Y-m-d'),
      ]);

      $response->assertSessionHasErrors('jumlah_hutang');
  }

    /**
     * TC-Hutang-16 : Hutang dengan catatan kurang dari minimum (10 karakter)
     */
    public function test_hutang_catatan_kurang_dari_batas_minimum()
    {
        $response = $this->post(route('hutang.store'), [
            'nama' => 'Ira',
            'catatan' => 'ppppppppp', //9 karakter
            'jumlah_hutang' => 100000,
            'jumlah_cicilan' => 1,
            'tanggal_pinjaman' => '2024-11-09',
            'tanggal_jatuh_tempo' => '2024-11-10',
        ]);

        $response->assertSessionHasErrors('catatan');
        $response->assertSee('The catatan field must at least 10 characters');
    }

    /**
     * TC-Hutang-17 : Hutang dengan catatan lebih dari maksimum (255 karakter)
     */
    public function test_hutang_catatan_lebih_dari_batas_maksimum()
    {

        $response = $this->post(route('hutang.store'), [
            'nama' => 'Ira',
            'catatan' => str_repeat('a', 266), //266 karakter
            'jumlah_hutang' => 100000,
            'jumlah_cicilan' => 1,
            'tanggal_pinjaman' => '2024-11-09',
            'tanggal_jatuh_tempo' => '2024-11-10',
        ]);

        $response->assertSessionHasErrors('catatan');
        $response->assertSee('The catatan field must not be greater than 255 characters');
    }

    /**
     * TC-Hutang-18: Admin menambahkan hutang dengan cicilan lebih dari batas maksimum (60)
     */
    public function test_hutang_cicilan_lebih_dari_batas_maksimum()
    {
        $response = $this->post(route('hutang.store'), [
            'nama' => 'Ira',
            'catatan' => 'Pinjaman untuk modal usaha',
            'jumlah_hutang' => 10000000,
            'jumlah_cicilan' => 61,
            'tanggal_pinjaman' => '2024-11-09',
            'tanggal_jatuh_tempo' => '2027-11-09',
        ]);

        $response->assertSessionHasErrors('jumlah_cicilan');
        $response->assertSee('The jumlah_cicilan field must not be greater than 60');
    }
}