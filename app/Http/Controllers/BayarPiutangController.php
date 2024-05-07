<?php

namespace App\Http\Controllers;

use App\Models\BayarPiutang;
use Illuminate\Http\Request;

use App\Models\Piutang;

class BayarPiutangController extends Controller{
    public function index()
    {
        $bayarpiutang = BayarPiutang::all();
        $piutangs = piutang::where('id_usaha', auth()->user()->id_usaha)->get(); 
        return view('pembayaran.piutang', compact('bayarpiutang','piutangs')); 
   
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

        $piutang = Piutang::where('nama', $request->nama)->firstOrFail();

        $sisa_piutang = $piutang->jumlah_piutang - $request->jumlah;
        $piutang->sisa_piutang = $sisa_piutang;

        $piutang->jumlah_cicilan -= 1;
        if ($sisa_piutang <= 0) {
            $piutang->status = 'Lunas';
        }
        $piutang->save();
        // Simpan data pembayaran hutang
        BayarPiutang::create([
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'nama' => $request->nama,
            'pembayaran' => $request->pembayaran,
            'jumlah' => $request->jumlah,
            'id_usaha' => $piutang->id_usaha,
        ]);

        return redirect()->route('piutang.index')->with('success', 'Pembayaran piutang berhasil disimpan.');
    }
}
