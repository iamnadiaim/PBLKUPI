<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BayarHutang;
use App\Models\Hutang; // Tambahkan impor untuk model Hutang

class BayarHutangController extends Controller
{
    // Menampilkan daftar pembayaran hutang
    public function index()
    {
        $bayarhutang = BayarHutang::where('id_usaha', auth()->user()->id_usaha)->get();
        return view('pembayaran.hutang', compact('bayarhutang'));
    }

    // Menyimpan pembayaran hutang baru
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'id_hutang' => 'required',
            'tanggal_pembayaran' => 'required',
            'nama' => 'required',
            'pembayaran' => 'required',
            'jumlah' =>  'required|numeric|min:1',
            // Sesuaikan dengan aturan validasi yang Anda butuhkan
        ]);

        // Perbarui sisa hutang pada entri hutang yang terkait
        $hutang = Hutang::findOrFail($request->id_hutang); // Perbaiki penamaan model Hutang
        $hutang->sisa_hutang -= $request->jumlah;
        $hutang->save();

        // Tentukan status pembayaran
        $status = ($hutang->sisa_hutang === 0) ? 'Lunas' : 'Belum Lunas';

        // Simpan data pembayaran hutang
        BayarHutang::create($request->all());

        return redirect()->route('hutang.index')
            ->with('simpan', 'Pembayaran Hutang berhasil disimpan')
            ->with('status', $status);
    }
}
