<?php

namespace App\Http\Controllers;

use App\Models\BayarHutang;
use Illuminate\Http\Request;
use App\Models\hutang;

class BayarHutangController extends Controller{
    public function index()
    {
        $bayarhutang = BayarHutang::all();
        $hutangs = hutang::where('id_usaha', auth()->user()->id_usaha)->get(); 
        return view('pembayaran.hutang', compact('bayarhutang','hutangs')); 
   
        // Logika untuk menampilkan halaman pembayaran hutang
    }

    public function store(Request $request) {
        // Validasi input
        $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'nama' => 'required|string',
            'pembayaran' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $hutang = hutang::where('nama', $request->nama)->firstOrFail();

        $sisa_hutang = $hutang->sisa_hutang - $request->jumlah;
        $hutang->sisa_hutang = max($sisa_hutang, 0);
        
        if ($hutang->jumlah_cicilan > 0) {
            $hutang->jumlah_cicilan -= 1;
        }
        
        if ($hutang->jumlah_cicilan <= 0 || $hutang->sisa_hutang <= 0) {
            $hutang->status = 'Lunas';
        }
        $hutang->save();
        // Simpan data pembayaran hutang
        BayarHutang::create([
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'nama' => $request->nama,
            'pembayaran' => $request->pembayaran,
            'jumlah' => $request->jumlah,
            'id_usaha' => $hutang->id_usaha,
        ]);
        $belum_lunas = hutang::where('id_usaha', auth()->user()->id_usaha)
                                ->where('status', '<>', 'Lunas')
                                ->exists();

        if (!$belum_lunas) {
        // Do something if all debts are paid (optional)
        }

        return redirect()->route('hutang.index')->with('success', 'Pembayaran hutang berhasil disimpan.');
    }
}