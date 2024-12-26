<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beban;
use App\Models\Pendapatan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $pendapatan = Pendapatan::with('produk')->where('id_usaha', auth()->user()->id_usaha)->get();

        return response()->json($pendapatan);
    }

    public function indexBeban()
    {
        $pengeluaran = Beban::with('kategori')->where('id_usaha', auth()->user()->id_usaha)->get();

        return response()->json($pengeluaran);
    }
}
