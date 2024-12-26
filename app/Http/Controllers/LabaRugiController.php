<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beban;
use App\Models\Kategori;
use App\Models\Pendapatan;
use App\Http\Controllers\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    public function hitungLabaRugi()
{
    Carbon::setLocale('id');    
    $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);
    $selectedMonth = request('month');
    
    // Validate the selected month to avoid potential issues
    try {
        $selectedDate = Carbon::parse($selectedMonth);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Invalid date format.');
    }
    
    // Menghitung total pendapatan
    $totalPendapatan = Pendapatan::where('id_usaha', Auth::user()->usaha->id)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->sum('total');
    
    // Menghitung total beban
    $totalBeban = Beban::where('id_usaha', Auth::user()->usaha->id)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->sum('harga');
    
    // Menghitung laba rugi
    $labaRugi = $totalPendapatan - $totalBeban;

    $kategoris = Kategori::with(['bebans' => function ($query) use ($selectedDate) {
        $query
        ->where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month);
    }])->where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();

    $bulan = [
        [
            'indo' => "Januari",
            'inggris' => "January"
        ],
        [
            'indo' => "Februari",
            'inggris' => "February"
        ],
        [
            'indo' => "Maret",
            'inggris' => "March"
        ],    
        [
            'indo' => "April",
            'inggris' => "April"
        ],
        [
            'indo' => "Mei",
            'inggris' => "May"
        ], 
        [
            'indo' => "Juni",
            'inggris' => "June"
        ],    
        [
            'indo' => "Juli",
            'inggris' => "July"
        ],
        [
            'indo' => "Agustus",
            'inggris' => "August"
        ],    
        [
            'indo' => "September",
            'inggris' => "September"
        ],    
        [
            'indo' => "Oktober",
            'inggris' => "October"
        ],    
        [
            'indo' => "November",
            'inggris' => "November"
        ],    
        [
            'indo' => "Desember",
            'inggris' => "December"
        ],    
        ];

    return view('labarugi', compact('selectedDate', 'namaUsaha', 'totalPendapatan', 'totalBeban', 'labaRugi', 'kategoris', 'bulan'));
}
public function print() {
    $namaUsaha = strtoupper(Auth::user()->usaha->nama_usaha);
    $selectedMonth = request('month');
    
    // Validate the selected month to avoid potential issues
    try {
        $selectedDate = Carbon::parse($selectedMonth);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Invalid date format.');
    }
    $totalPendapatan = Pendapatan::where('id_usaha', Auth::user()->usaha->id)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->sum('total');
        $totalBeban = Beban::where('id_usaha', Auth::user()->usaha->id)
        ->whereYear('created_at', $selectedDate->year)
        ->whereMonth('created_at', $selectedDate->month)
        ->sum('harga');
        $labaRugi = $totalPendapatan - $totalBeban;

    $kategoris = Kategori::with(['bebans' => function ($query) use ($selectedDate) {
        $query
        ->where('id_usaha', Auth::user()->usaha->id)
            ->whereYear('created_at', $selectedDate->year)
            ->whereMonth('created_at', $selectedDate->month);
    }])->where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();

    $bulan = [
        [
            'indo' => "Januari",
            'inggris' => "January"
        ],
        [
            'indo' => "Februari",
            'inggris' => "February"
        ],
        [
            'indo' => "Maret",
            'inggris' => "March"
        ],    
        [
            'indo' => "April",
            'inggris' => "April"
        ],
        [
            'indo' => "Mei",
            'inggris' => "May"
        ], 
        [
            'indo' => "Juni",
            'inggris' => "June"
        ],    
        [
            'indo' => "Juli",
            'inggris' => "July"
        ],
        [
            'indo' => "Agustus",
            'inggris' => "August"
        ],    
        [
            'indo' => "September",
            'inggris' => "September"
        ],    
        [
            'indo' => "Oktober",
            'inggris' => "October"
        ],    
        [
            'indo' => "November",
            'inggris' => "November"
        ],    
        [
            'indo' => "Desember",
            'inggris' => "December"
        ],    
        ];
    // Mengirimkan data laba rugi ke view 'cetaklabarugi'
    return view('cetaklabarugi', compact('selectedDate','labaRugi','namaUsaha', 'totalPendapatan', 'totalBeban', 'kategoris', 'bulan'));
}
// public function laporan(){
//     // dd("tes");
//     return view('tampilanpegawai',compact('pegawais'));
// }

    
    
}
 