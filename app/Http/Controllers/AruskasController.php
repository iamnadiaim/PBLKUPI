<?php

namespace App\Http\Controllers;

use App\Models\Beban;
use App\Models\Kategori;
use App\Models\Pendapatan;
use App\Models\Saldo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArusKasController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');    
        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);
        $selectedMonth = request('month');
        try {
            $selectedDate = Carbon::parse($selectedMonth);
        } catch (\Exception $e) {
            $e;
        }

        $cekSaldo = Saldo::where('id_usaha', auth()->user()->id_usaha)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->first();
        
        if (!$cekSaldo) {
            $saldoAwal = 0; // Atur nilai saldo awal sesuai kebutuhan
            $cekSaldo = new Saldo([
                'id_usaha' => auth()->user()->id_usaha,
                'saldo' => $saldoAwal,
                // Tambahkan atribut lainnya sesuai kebutuhan
            ]);
        } else {
            $saldoAwal = $cekSaldo->saldo;
        }

        // $arusses = ArusKasControllers::all();
        $kategoris = Kategori::with(['bebans' => function ($query) use ($selectedDate) {
            $query
            ->where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month);
        }])->where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();

        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('total');
        
        // Menggunakan query builder Laravel
        $totalBeban = Beban::where('id_usaha', auth()->user()->id_usaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('harga');

        // Menghitung laba rugi
        if ($cekSaldo) {
            $saldoAkhir = $cekSaldo->saldo - $totalBeban;
        } else {
            $saldoAkhir = 0;
        }
        $bulan = [
            [
                'indo' => "januari",
                'inggris' => "january"
            ],
            [
                'indo' => "februari",
                'inggris' => "february"
            ],
            [
                'indo' => "maret",
                'inggris' => "march"
            ],    
            [
                'indo' => "april",
                'inggris' => "april"
            ],
            [
                'indo' => "mei",
                'inggris' => "may"
            ], 
            [
                'indo' => "juni",
                'inggris' => "june"
            ],    
            [
                'indo' => "juli",
                'inggris' => "july"
            ],
            [
                'indo' => "agustus",
                'inggris' => "august"
            ],    
            [
                'indo' => "september",
                'inggris' => "september"
            ],    
            [
                'indo' => "oktober",
                'inggris' => "october"
            ],    
            [
                'indo' => "november",
                'inggris' => "november"
            ],    
            [
                'indo' => "desember",
                'inggris' => "december"
            ],    
            ];
        return view('aruskas',compact('kategoris','totalPendapatan','cekSaldo','saldoAkhir', 'bulan'));
    }
    public function print() {
        
        Carbon::setLocale('id');    
        $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);
        $selectedMonth = request('month');
        try {
            $selectedDate = Carbon::parse($selectedMonth);
        } catch (\Exception $e) {
            $e;
        }

        $cekSaldo = Saldo::where('id_usaha', auth()->user()->id_usaha)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->first();
        
        if (!$cekSaldo) {
            $saldoAwal = 0; // Atur nilai saldo awal sesuai kebutuhan
            $cekSaldo = new Saldo([
                'id_usaha' => auth()->user()->id_usaha,
                'saldo' => $saldoAwal,
                // Tambahkan atribut lainnya sesuai kebutuhan
            ]);
        } else {
            $saldoAwal = $cekSaldo->saldo;
        }

        // $arusses = ArusKasControllers::all();
        $kategoris = Kategori::with(['bebans' => function ($query) use ($selectedDate) {
            $query
            ->where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month);
        }])->where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();

        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('total');
        
        // Menggunakan query builder Laravel
        $totalBeban = Beban::where('id_usaha', auth()->user()->id_usaha)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month)
            ->sum('harga');

        // Menghitung laba rugi
        if ($cekSaldo) {
            $saldoAkhir = $cekSaldo->saldo - $totalBeban;
        } else {
            $saldoAkhir = 0;
        }
        $bulan = [
            [
                'indo' => "januari",
                'inggris' => "january"
            ],
            [
                'indo' => "februari",
                'inggris' => "february"
            ],
            [
                'indo' => "maret",
                'inggris' => "march"
            ],    
            [
                'indo' => "april",
                'inggris' => "april"
            ],
            [
                'indo' => "mei",
                'inggris' => "may"
            ], 
            [
                'indo' => "juni",
                'inggris' => "june"
            ],    
            [
                'indo' => "juli",
                'inggris' => "july"
            ],
            [
                'indo' => "agustus",
                'inggris' => "august"
            ],    
            [
                'indo' => "september",
                'inggris' => "september"
            ],    
            [
                'indo' => "oktober",
                'inggris' => "october"
            ],    
            [
                'indo' => "november",
                'inggris' => "november"
            ],    
            [
                'indo' => "desember",
                'inggris' => "december"
            ],    
            ];
        return view('cetakaruskas',compact( 'namaUsaha','selectedDate','selectedMonth','kategoris','totalBeban','totalPendapatan','cekSaldo','saldoAkhir', 'bulan'));
    }}
