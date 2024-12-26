<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beban;
use App\Models\Pendapatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $kasMasuk = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->sum('total');
        
        $kasKeluar = Beban::where('id_usaha', auth()->user()->id_usaha)->sum('harga');

        $totalTransaksi = Pendapatan::where('id_usaha', auth()->user()->id_usaha)->orderBy('created_at', 'desc')->count();

        return response()->json(['totalPendapatan' => $kasMasuk, "totalBeban" => $kasKeluar, 'totalTransaksi' => $totalTransaksi]);
    }
}
