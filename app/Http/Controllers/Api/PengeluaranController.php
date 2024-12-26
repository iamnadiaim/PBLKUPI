<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beban;
use App\Models\Kategori;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index() 
    {
        $kategoris = Kategori::all();
        // Render the view
        return response()->json($kategoris);

    }
    public function store(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'nama' => 'required',
            'id_kategori' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'harga' => 'required|numeric'
            // Sesuaikan validasi dengan kebutuhan Anda
        ]);
        
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        // Simpan data beban baru ke dalam database
        beban::create($validatedData);

        return response()->json(['message'=>'Pengeluaran Berhasil Ditambah']); // Redirect ke halaman detail beban dengan pesan sukses
    }
}
