<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Beban;
use App\Models\Pendapatan;
use App\Models\Saldo;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    public function index(): View
    { 
        // Ambil nama usaha
        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);
        
        // Cek saldo
        $cekSaldo = Saldo::where('id_usaha', auth()->user()->id_usaha)->first();

        // Total kas masuk dan keluar
        $kasMasuk = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        $kasMasukFormat = 'Rp ' . number_format($kasMasuk, 0, ',', '.');
        
        $kasKeluar = Beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');
        $kasKeluarFormat = 'Rp ' . number_format($kasKeluar, 0, ',', '.');

        // Hitung saldo akhir (jika ada modal awal)
        if($cekSaldo){
            $saldoAkhir = $cekSaldo->saldo - $kasKeluar;
        }else{
            $saldoAkhir = 0;
        }

        // Hitung jumlah pendapatan
        $pendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->orderBy('created_at', 'desc')->count();

        $allLabaRugi = $kasMasuk - $kasKeluar;
        $allLabaRugiFormat = 'Rp ' . number_format($allLabaRugi, 0, ',', '.');

        // Mengambil data pendapatan dan beban bulanan untuk chart laba/rugi
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
        $kasMasukData = array_fill(1, 12, 0);
        $kasKeluarData = array_fill(1, 12, 0);
        foreach (range(1, 12) as $bulan) {
            $kasMasukData[$bulan] = $kasMasukBulanan[$bulan]['total'] ?? 0;
            $kasKeluarData[$bulan] = $kasKeluarBulanan[$bulan]['total'] ?? 0;
        }
    
         // Format ke dalam opsi chart kas masuk dan keluar
        $chartOptions = [
            'series' => [
                [
                    'name' => 'Kas Masuk',
                    'data' => array_values($kasMasukData),
                ],
                [
                    'name' => 'Kas Keluar',
                    'data' => array_values($kasKeluarData),
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ],
        ];
        // Mengambil 5 produk terlaris berdasarkan jumlah produk terjual
        $produkTerlaris = Produk::select('nama_produk', DB::raw('SUM(p.jumlah_produk) as total_terjual'))
            ->join('pendapatans as p', 'produks.id', '=', 'p.id_produk')
            ->where('produks.id_usaha', auth()->user()->id_usaha)
            ->groupBy('produks.id', 'nama_produk')
            ->orderByDesc('total_terjual')
            ->limit(5) // Menampilkan 5 produk terlaris
            ->get();
        // Opsi grafik produk terlaris
        $produkChartOptions = [
            'chart' => [
                'type' => 'bar', // Menggunakan grafik batang
            ],
            'series' => [
                [
                    'name' => 'Jumlah Terjual',
                    'data' => $produkTerlaris->pluck('total_terjual')->toArray(), // Mengambil data jumlah terjual
                ],
            ],
            'xaxis' => [
                'categories' => $produkTerlaris->pluck('nama_produk')->toArray(), // Mengambil nama produk
            ],
        ];
    
        return view('dashboard', compact(
            'kasKeluarFormat',
            'kasMasukFormat',
            'chartOptions',
            'pendapatan',
            'cekSaldo',
            'allLabaRugi',
            'allLabaRugiFormat',
            'kasMasuk',
            'kasKeluar',
            'saldoAkhir',
            'namaUsaha',
            'produkTerlaris',
            'produkChartOptions' // Mengirimkan opsi grafik produk terlaris ke view
        ));
    }   
}
