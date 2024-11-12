<?php

namespace Tests\Unit;

use App\Models\hutang;
use App\Models\piutang;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanHutangPiutangTest extends TestCase
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

    public function test_see_laporan_hutang_piutang()
    {
        hutang::insert([[
            "tanggal_pinjaman" => "2024-11-11",
            "tanggal_jatuh_tempo" => "2024-11-29",
            "nama" => "Loki",
            "jumlah_hutang" => "100000",
            "jumlah_cicilan" => "20000",
            "catatan" => "catatan",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "100000",
            "status" => false,
            'updated_at' => now(),
            'created_at' => now()
        ],[
            "tanggal_pinjaman" => "2024-12-10",
            "tanggal_jatuh_tempo" => "2024-12-29",
            "nama" => "Nikita",
            "jumlah_hutang" => "10000",
            "jumlah_cicilan" => "2000",
            "catatan" => "catatan ya",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "10000",
            'updated_at' => now(),
            'created_at' => now(),
            "status" => false
            ]
        ]);

        piutang::insert([[
            "tanggal_pinjaman" => "2024-11-10",
            "tanggal_jatuh_tempo" => "2024-11-28",
            "nama" => "Rey",
            "jumlah_piutang" => "322311",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang A",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now(),
            "sisa_piutang" => "322311"
        ],
        [
            "tanggal_pinjaman" => "2024-12-25",
            "tanggal_jatuh_tempo" => "2024-12-30",
            "nama" => "Mikasa",
            "jumlah_piutang" => "123456",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang B",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now(),
            "sisa_piutang" => "123456"
        ]]);

        $this->assertDatabaseHas('piutangs', [
            "nama" => "Mikasa",
        ]);
        
        $this->assertDatabaseHas('piutangs', [
            "nama" => "Rey",
        ]);
        

        $response = $this->get(route('laporanhutang.index'));
        $response->assertStatus(200);

        $response->assertSeeText('2024-11-11');
        $response->assertSeeText('Loki');
        $response->assertSeeText('2024-12-10');
        $response->assertSeeText('Nikita');
        $response->assertSeeText('2024-11-10');
        $response->assertSeeText('Rey');
        $response->assertSeeText('2024-12-25');
        $response->assertSeeText('Mikasa');
        
    }

    public function test_see_laporan_hutang_piutang_with_filter()
    {
        hutang::insert([[
            "tanggal_pinjaman" => "2024-11-11",
            "tanggal_jatuh_tempo" => "2024-11-29",
            "nama" => "Loki",
            "jumlah_hutang" => "100000",
            "jumlah_cicilan" => "20000",
            "catatan" => "catatan",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "100000",
            "status" => false,
            'updated_at' => now(),
            'created_at' => now()->subMonth()
        ],[
            "tanggal_pinjaman" => "2024-12-10",
            "tanggal_jatuh_tempo" => "2024-12-29",
            "nama" => "Nikita",
            "jumlah_hutang" => "10000",
            "jumlah_cicilan" => "2000",
            "catatan" => "catatan ya",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "10000",
            'updated_at' => now(),
            'created_at' => now()->addMonth(),
            "status" => false
            ]
        ]);

        piutang::insert([[
            "tanggal_pinjaman" => "2024-11-10",
            "tanggal_jatuh_tempo" => "2024-11-28",
            "nama" => "Rey",
            "jumlah_piutang" => "322311",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang A",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now()->subMonth(),
            "sisa_piutang" => "322311"
        ],
        [
            "tanggal_pinjaman" => "2024-12-25",
            "tanggal_jatuh_tempo" => "2024-12-30",
            "nama" => "Mikasa",
            "jumlah_piutang" => "123456",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang B",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now()->addMonth(),
            "sisa_piutang" => "123456"
        ]]);

        $response = $this->get('laporanhutang?month=december');
        $response->assertStatus(200);

        $response->assertDontSeeText('2024-11-11');
        $response->assertDontSeeText('Loki');
        $response->assertSeeText('2024-12-10');
        $response->assertSeeText('Nikita');
        $response->assertDontSeeText('2024-11-10');
        $response->assertDontSeeText('Rey');
        $response->assertSeeText('2024-12-25');
        $response->assertSeeText('Mikasa');
        
    }

    public function test_print_laporan_hutang_piutang_with_filter()
    {
        hutang::insert([[
            "tanggal_pinjaman" => "2024-11-11",
            "tanggal_jatuh_tempo" => "2024-11-29",
            "nama" => "Loki",
            "jumlah_hutang" => "100000",
            "jumlah_cicilan" => "20000",
            "catatan" => "catatan",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "100000",
            "status" => false,
            'updated_at' => now(),
            'created_at' => now()->subMonth()
        ],[
            "tanggal_pinjaman" => "2024-12-10",
            "tanggal_jatuh_tempo" => "2024-12-29",
            "nama" => "Nikita",
            "jumlah_hutang" => "10000",
            "jumlah_cicilan" => "2000",
            "catatan" => "catatan ya",
            "id_usaha" => auth()->user()->id_usaha,
            "sisa_hutang" => "10000",
            'updated_at' => now(),
            'created_at' => now()->addMonth(),
            "status" => false
            ]
        ]);

        piutang::insert([[
            "tanggal_pinjaman" => "2024-11-10",
            "tanggal_jatuh_tempo" => "2024-11-28",
            "nama" => "Rey",
            "jumlah_piutang" => "322311",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang A",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now()->subMonth(),
            "sisa_piutang" => "322311"
        ],
        [
            "tanggal_pinjaman" => "2024-12-25",
            "tanggal_jatuh_tempo" => "2024-12-30",
            "nama" => "Mikasa",
            "jumlah_piutang" => "123456",
            "jumlah_cicilan" => "32",
            "catatan" => "Piutang B",
            "id_usaha" => auth()->user()->id_usaha,
            'updated_at' => now(),
            'created_at' => now()->addMonth(),
            "sisa_piutang" => "123456"
        ]]);

        $response = $this->get('laporan-hutang-piutang/print?month=december');
        $response->assertStatus(200);

        $response->assertDontSeeText('2024-11-11');
        $response->assertDontSeeText('Loki');
        $response->assertSeeText('2024-12-10');
        $response->assertSeeText('Nikita');
        $response->assertDontSeeText('2024-11-10');
        $response->assertDontSeeText('Rey');
        $response->assertSeeText('2024-12-25');
        $response->assertSeeText('Mikasa');
        $response->assertSeeText('window.print();');
    }
}
