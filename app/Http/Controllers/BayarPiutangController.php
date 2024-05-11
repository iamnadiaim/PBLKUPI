<?php

namespace App\Http\Controllers;
use App\Models\BayarPiutang;
use Illuminate\Http\Request;

use App\Models\Piutang;

class BayarPiutangController extends Controller
{
    public function index()
    {
        $bayarpiutang = BayarPiutang::all();
        $piutangs = Piutang::where('id_usaha', auth()->user()->id_usaha)->get(); 
        return view('pembayaran.piutang', compact('bayarpiutang', 'piutangs')); 
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'nama' => 'required|string',
            'pembayaran' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $piutang = Piutang::where('nama', $request->nama)->first();

        if (!$piutang) {
            return redirect()->back()->with('error', 'Nama piutang tidak ditemukan');
        }

        if ($piutang->jumlah_cicilan <= 0) {
            return redirect()->back()->with('error', 'Jumlah cicilan sudah habis');
        }

        if ($request->jumlah > $piutang->sisa_piutang) {
            return redirect()->back()->with('error', 'Jumlah melebihi sisa piutang');
        }

        $sisa_piutang = $piutang->sisa_piutang - $request->jumlah;
        $piutang->sisa_piutang = $sisa_piutang;

        $piutang->jumlah_cicilan -= 1;
        if ($sisa_piutang <= 0) {
            $piutang->status = true; // Mengubah status menjadi true jika lunas
        }
        $piutang->save();

        // Simpan data pembayaran piutang
        BayarPiutang::create([
            'id_piutang' => $piutang->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'nama' => $request->nama,
            'pembayaran' => $request->pembayaran,
            'jumlah' => $request->jumlah,
            'id_usaha' => $piutang->id_usaha,
        ]);
        $belum_lunas = piutang::where('id_usaha', auth()->user()->id_usaha)
                                ->where('status', '<>', 'Lunas')
                                ->exists();

        if (!$belum_lunas) {
        // Do something if all debts are paid (optional)
        }

        return redirect()->route('piutang.index')->with('success', 'Pembayaran piutang berhasil disimpan.');
    }
}
