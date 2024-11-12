<?php

namespace Tests\Unit;

use App\Models\Saldo;
use Tests\TestCase;
use App\Models\Beban;
use App\Models\Produk;
use App\Models\Pendapatan;
use App\Models\jenisBarang;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
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


    public function test_see_dashboard(){
        
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        Produk::insert([
            [
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '010',
                'nama_produk' => 'Sale Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '200gr',
                'harga' => 15000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '011',
                'nama_produk' => 'Pisang Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 10000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '012',
                'nama_produk' => 'Nanas Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 5000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '013',
                'nama_produk' => 'Kripik Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 25000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '014',
                'nama_produk' => 'Bolu Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 20000,
                'stok' => 111,
            ]]);
            
        $productIdA = Produk::where('kode_produk', '010')->first();
        $productIdB = Produk::where('kode_produk', '011')->first();
        $productIdC = Produk::where('kode_produk', '012')->first();
        $productIdD = Produk::where('kode_produk', '013')->first();
        $productIdE = Produk::where('kode_produk', '014')->first();
        
        Pendapatan::insert([
            [
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "2",
                "nama_pembeli" => "Rini",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 2,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdB->id,
                "jumlah_produk" => "5",
                "nama_pembeli" => "Rani",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdB->harga,
                "total" => $productIdB->harga * 5 ,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdC->id,
                "jumlah_produk" => "10",
                "nama_pembeli" => "Rian",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdC->harga,
                "total" => $productIdC->harga * 10,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdD->id,
                "jumlah_produk" => "7",
                "nama_pembeli" => "Rino",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdD->harga,
                "total" => $productIdD->harga * 7,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdE->id,
                "jumlah_produk" => "6",
                "nama_pembeli" => "Riko",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdE->harga,
                "total" => $productIdE->harga * 6,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "3",
                "nama_pembeli" => "Rizal",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 3,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);

        beban::insert([
            [
                "tanggal" => "2024-11-11",
                "nama" => "Kompor",
                "id_kategori" => "2",
                "jumlah" => "1",
                "harga" => "200000",
                "id_usaha" => auth()->user()->id_usaha,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "nama" => "Kulkas",
                "id_kategori" => "2",
                "jumlah" => "1",
                "harga" => "900000",
                "id_usaha" => auth()->user()->id_usaha,
                "created_at" => now(),
                "updated_at" => now()
            ]
            ]);

            $response = $this->get('/dashboard');

            $response->assertStatus(200);
            $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
            $totalPendapatanFormat = 'Rp ' . number_format($totalPendapatan, 0, ',', '.');
            $totalBeban = beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');
            $totalBebanFormat = 'Rp ' . number_format($totalBeban, 0, ',', '.');
            $labaRugi = $totalPendapatan - $totalBeban;
            $labaRugiFormat = 'Rp ' . number_format($labaRugi, 0, ',', '.');
            $totalPembelian = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->count();

            $response->assertSeeText($totalPendapatanFormat);
            $response->assertSeeText($totalBebanFormat);
            $response->assertSeeText($labaRugiFormat);
            $response->assertSeeText($totalPembelian);

            $produkTerlaris = Produk::select('nama_produk', DB::raw('SUM(p.jumlah_produk) as total_terjual'))
            ->join('pendapatans as p', 'produks.id', '=', 'p.id_produk')
            ->where('produks.id_usaha', auth()->user()->id_usaha)
            ->groupBy('produks.id', 'nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->toArray();
        
            $kasMasukBulanan = Pendapatan::selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
            ->where('id_usaha', auth()->user()->id_usaha)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan')
            ->toArray();

        $kasKeluarBulanan = Beban::selectRaw('MONTH(created_at) as bulan, SUM(harga) as total')
            ->where('id_usaha', auth()->user()->id_usaha)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan')
            ->toArray();

        // Inisialisasi data kas masuk dan kas keluar untuk 12 bulan
        $kasMasuk = array_fill(1, 12, 0);
        $kasKeluar = array_fill(1, 12, 0);

        foreach (range(1, 12) as $bulan) {
            $kasMasuk[$bulan] = $kasMasukBulanan[$bulan]['total'] ?? 0;
            $kasKeluar[$bulan] = $kasKeluarBulanan[$bulan]['total'] ?? 0;
        }

        $produkTerlarisData = json_encode($produkTerlaris);
        $kasMasukData = json_encode((array_values($kasMasuk)));
        $kasKeluarData = json_encode((array_values($kasKeluar)));

        $produkTerlarisExpect = 'let produkTerlaris = ' . $produkTerlarisData . ';';
        $kasMasukExpect = 'let kasMasukData = ' . $kasMasukData . ';';
        $kasKeluarExpect = 'let kasKeluarData = ' . $kasKeluarData . ';';
            // dd($response->getContent());
        $response->assertSee($produkTerlarisExpect, false);
        $response->assertSee($kasMasukExpect, false);
        $response->assertSee($kasKeluarExpect, false);

        $response->assertSee('<div id="produkPieChart"></div>', false);
        $response->assertSee('<div id="arusKasChart"></div>', false);
        
    }

    public function test_dashboard_total_pendapatan(){

        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Rp ' . number_format($totalPendapatan, 0, ',', '.'));

        $response = $this->get(route('riwayat.index'));
        $response->assertStatus(200);
        // Verifikasi total pendapatan muncul di halaman riwayat
        $response->assertSee('Rp ' . number_format($totalPendapatan, 0, ',', '.'));
    }
    
    public function test_dashboard_total_beban(){
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $totalBeban = Beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');
        $response->assertSee('Rp ' . number_format($totalBeban, 0, ',', '.'));

        $response = $this->get(route('riwayatbeban'));
        $response->assertStatus(200);
        // Verifikasi total beban muncul di halaman riwayat
        $response->assertSee('Rp ' . number_format($totalBeban, 0, ',', '.'));
    }

    public function test_dashboard_laba_rugi(){
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        $totalBeban = Beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');
        $totalKeuntungan = $totalPendapatan - $totalBeban;
        $response->assertSee('Rp ' . number_format($totalKeuntungan, 0, ',', '.'));

        $response = $this->get(route('labarugi.index'));
        $response->assertStatus(200);
        $response->assertSee($totalKeuntungan);
    }

    public function test_dashboard_total_pembelian(){

        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        Produk::insert([
            [
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '010',
                'nama_produk' => 'Sale Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '200gr',
                'harga' => 15000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '011',
                'nama_produk' => 'Pisang Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 10000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '012',
                'nama_produk' => 'Nanas Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 5000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '013',
                'nama_produk' => 'Kripik Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 25000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '014',
                'nama_produk' => 'Bolu Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 20000,
                'stok' => 111,
            ]]);
            
        $productIdA = Produk::where('kode_produk', '010')->first();
        $productIdB = Produk::where('kode_produk', '011')->first();
        $productIdC = Produk::where('kode_produk', '012')->first();
        $productIdD = Produk::where('kode_produk', '013')->first();
        $productIdE = Produk::where('kode_produk', '014')->first();
        
        Pendapatan::insert([
            [
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "2",
                "nama_pembeli" => "Rini",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 2,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdB->id,
                "jumlah_produk" => "5",
                "nama_pembeli" => "Rani",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdB->harga,
                "total" => $productIdB->harga * 5 ,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdC->id,
                "jumlah_produk" => "10",
                "nama_pembeli" => "Rian",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdC->harga,
                "total" => $productIdC->harga * 10,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdD->id,
                "jumlah_produk" => "7",
                "nama_pembeli" => "Rino",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdD->harga,
                "total" => $productIdD->harga * 7,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdE->id,
                "jumlah_produk" => "6",
                "nama_pembeli" => "Riko",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdE->harga,
                "total" => $productIdE->harga * 6,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "3",
                "nama_pembeli" => "Rizal",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 3,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $totalPembelian = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->count();
        $response->assertSee($totalPembelian);
        $response = $this->get(route('riwayat.index'));
        // dd($totalPembelian, $response->getContent());
        $response->assertStatus(200);
        $responseContent = $response->getContent();

        preg_match_all('/<tr id="Pendapatan-\d+">/', $responseContent, $rowMatches);

        // Count the matched rows
        $rowCount = count($rowMatches[0]); // The actual matched rows are in $rowMatches[0]
    
        // Ensure the row count matches the totalPembelian
        $this->assertEquals($totalPembelian, $rowCount);
    }

    public function test_see_arus_kas_with_grafik(){
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        Produk::insert([
            [
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '010',
                'nama_produk' => 'Sale Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '200gr',
                'harga' => 15000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '011',
                'nama_produk' => 'Pisang Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 10000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '012',
                'nama_produk' => 'Nanas Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 5000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '013',
                'nama_produk' => 'Kripik Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 25000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '014',
                'nama_produk' => 'Bolu Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 20000,
                'stok' => 111,
            ]]);
            
        $productIdA = Produk::where('kode_produk', '010')->first();
        $productIdB = Produk::where('kode_produk', '011')->first();
        $productIdC = Produk::where('kode_produk', '012')->first();
        $productIdD = Produk::where('kode_produk', '013')->first();
        $productIdE = Produk::where('kode_produk', '014')->first();
        
        Pendapatan::insert([
            [
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "2",
                "nama_pembeli" => "Rini",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 2,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdB->id,
                "jumlah_produk" => "5",
                "nama_pembeli" => "Rani",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdB->harga,
                "total" => $productIdB->harga * 5 ,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdC->id,
                "jumlah_produk" => "10",
                "nama_pembeli" => "Rian",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdC->harga,
                "total" => $productIdC->harga * 10,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdD->id,
                "jumlah_produk" => "7",
                "nama_pembeli" => "Rino",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdD->harga,
                "total" => $productIdD->harga * 7,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdE->id,
                "jumlah_produk" => "6",
                "nama_pembeli" => "Riko",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdE->harga,
                "total" => $productIdE->harga * 6,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "3",
                "nama_pembeli" => "Rizal",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 3,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);

        beban::insert([
            [
                "tanggal" => "2024-11-11",
                "nama" => "Kompor",
                "id_kategori" => "2",
                "jumlah" => "1",
                "harga" => "200000",
                "id_usaha" => auth()->user()->id_usaha,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "nama" => "Kulkas",
                "id_kategori" => "2",
                "jumlah" => "1",
                "harga" => "900000",
                "id_usaha" => auth()->user()->id_usaha,
                "created_at" => now(),
                "updated_at" => now()
            ]
            ]);

        Saldo::insert([
            'id_usaha' => auth()->user()->id_usaha,
            'saldo' => '100000',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $kasMasukBulanan = Pendapatan::selectRaw('MONTH(created_at) as bulan, SUM(total) as total')
        ->where('id_usaha', auth()->user()->id_usaha)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get()
        ->keyBy('bulan')
        ->toArray();

        $kasKeluarBulanan = Beban::selectRaw('MONTH(created_at) as bulan, SUM(harga) as total')
            ->where('id_usaha', auth()->user()->id_usaha)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan')
            ->toArray();

        // Inisialisasi data kas masuk dan kas keluar untuk 12 bulan
        $kasMasuk = array_fill(1, 12, 0);
        $kasKeluar = array_fill(1, 12, 0);

        foreach (range(1, 12) as $bulan) {
            $kasMasuk[$bulan] = $kasMasukBulanan[$bulan]['total'] ?? 0;
            $kasKeluar[$bulan] = $kasKeluarBulanan[$bulan]['total'] ?? 0;
        }

        $kasMasukData = json_encode((array_values($kasMasuk)));
        $kasKeluarData = json_encode((array_values($kasKeluar)));

        $kasMasukExpect = 'let kasMasukData = ' . $kasMasukData . ';';
        $kasKeluarExpect = 'let kasKeluarData = ' . $kasKeluarData . ';';
        
        $response = $this->get(route('dashboard'));
        $response->assertSee($kasMasukExpect, false);
        $response->assertSee($kasKeluarExpect, false);

        $response->assertSee('<div id="arusKasChart"></div>', false);

        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        $modal = Saldo::where('id_usaha',auth()->user()->id_usaha)->get('saldo')->first();;
        $beban = Beban::where('id_usaha',auth()->user()->id_usaha)->sum('harga');

        // dd($modal);
        $saldoModal = (int)$modal->saldo;        
        // dd($saldoModal);
        $saldoAkhir = $saldoModal - $beban;
        $response = $this->get(route('aruskas.index'));
        $response->assertStatus(200);
        $response->assertSee('Rp. '. $saldoModal);

        // dd($saldoAkhir);
        $response->assertSee($totalPendapatan);
        $response->assertSee('Rp. '. $saldoAkhir);
    }

    public function test_five_product_upper(){
        $jenisBarang = jenisBarang::create([
            'id_usaha' => auth()->user()->id_usaha,
            'nama' => 'Makanan',
        ]); 

        Produk::insert([
            [
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '010',
                'nama_produk' => 'Sale Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '200gr',
                'harga' => 15000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '011',
                'nama_produk' => 'Pisang Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 10000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '012',
                'nama_produk' => 'Nanas Goreng',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 5000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '013',
                'nama_produk' => 'Kripik Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 25000,
                'stok' => 111,
            ],[
                "id_usaha" => auth()->user()->id_usaha,
                'kode_produk' => '014',
                'nama_produk' => 'Bolu Pisang',
                'id_jenis_barang' => $jenisBarang->id,
                'ukuran' => '20gr',
                'harga' => 20000,
                'stok' => 111,
            ]]);
            
        $productIdA = Produk::where('kode_produk', '010')->first();
        $productIdB = Produk::where('kode_produk', '011')->first();
        $productIdC = Produk::where('kode_produk', '012')->first();
        $productIdD = Produk::where('kode_produk', '013')->first();
        $productIdE = Produk::where('kode_produk', '014')->first();
        
        Pendapatan::insert([
            [
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "2",
                "nama_pembeli" => "Rini",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 2,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdB->id,
                "jumlah_produk" => "5",
                "nama_pembeli" => "Rani",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdB->harga,
                "total" => $productIdB->harga * 5 ,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdC->id,
                "jumlah_produk" => "10",
                "nama_pembeli" => "Rian",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdC->harga,
                "total" => $productIdC->harga * 10,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdD->id,
                "jumlah_produk" => "7",
                "nama_pembeli" => "Rino",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdD->harga,
                "total" => $productIdD->harga * 7,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdE->id,
                "jumlah_produk" => "6",
                "nama_pembeli" => "Riko",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdE->harga,
                "total" => $productIdE->harga * 6,
                "created_at" => now(),
                "updated_at" => now()
            ],[
                "tanggal" => "2024-11-11",
                "id_produk" => $productIdA->id,
                "jumlah_produk" => "3",
                "nama_pembeli" => "Rizal",
                "id_usaha" => auth()->user()->id_usaha,
                "harga_produk" => $productIdA->harga,
                "total" => $productIdA->harga * 3,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ]);

        $produkTerlaris = Produk::select('nama_produk', DB::raw('SUM(p.jumlah_produk) as total_terjual'))
            ->join('pendapatans as p', 'produks.id', '=', 'p.id_produk')
            ->where('produks.id_usaha', auth()->user()->id_usaha)
            ->groupBy('produks.id', 'nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->toArray();

        $produkTerlarisData = json_encode($produkTerlaris);
        $produkTerlarisExpect = 'let produkTerlaris = ' . $produkTerlarisData . ';';

        $response = $this->get(route('dashboard'));
        $response->assertSee($produkTerlarisExpect, false);
        $response->assertSee('<div id="produkPieChart"></div>', false);

        $response = $this->get(route('riwayat.index'));
        $response->assertStatus(200);

        $produkTerlarisData = json_decode($produkTerlarisData, true);  // Dekode data produk terlaris menjadi array asosiatif

        foreach ($produkTerlaris as $key => $produk) {
            // Akses menggunakan array key (misalnya $produk['nama_produk'])
            $this->assertEquals($produk['nama_produk'], $produkTerlarisData[$key]['nama_produk']);
            $this->assertEquals($produk['total_terjual'], $produkTerlarisData[$key]['total_terjual']);
        }
    }
}
