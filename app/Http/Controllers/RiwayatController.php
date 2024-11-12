<?php

namespace App\Http\Controllers;

use App\Models\Pendapatan;
use App\Models\Beban;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(): View
    {
        $pendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->orderBy('created_at', 'desc')->get();
        $totalPendapatan = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        $totalPendapatanFormated = number_format($totalPendapatan, 0, ',', '.');
        return view('riwayat', [
            'pendapatan' => $pendapatan,
            'totalPendapatan' => $totalPendapatanFormated
        ]);
    }

    public function indexBeban(): View
    {
        $beban = Beban::where('id_usaha', auth()->user()->id_usaha)->orderBy('created_at', 'desc')->get();
        $totalBeban = Beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');
        $totalBebanFormated = number_format($totalBeban, 0, ',', '.');
        return view('riwayatbeban', [
            'beban' => $beban,
            'totalBeban' => $totalBebanFormated
        ]);
    }
    
}