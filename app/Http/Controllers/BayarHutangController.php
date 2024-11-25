<?php
namespace App\Http\Controllers;

use App\Models\BayarHutang;
use Illuminate\Http\Request;
use App\Models\Hutang;

class BayarHutangController extends Controller
{
    public function index(Request $request)
    {
        $hutangs = Hutang::where('id_usaha', auth()->user()->id_usaha)->get();
        $bayarhutang = BayarHutang::all();
        return view('pembayaran.hutang', compact('bayarhutang', 'hutangs'));
    }

    public function create($id)
    {
        $hutang = Hutang::findOrFail($id);
        return view('pembayaran.hutang', compact('hutang'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'tanggal_pembayaran' => 'required|date|before_or_equal:today',
            'nama' => 'required|string',
            'pembayaran' => 'required|string|min:4|max:20',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $hutang = Hutang::findOrFail($request->id);

        if ($hutang->jumlah_cicilan <= 0) {
            return redirect()->back()->with('error', 'Jumlah cicilan sudah habis.');
        }

        if ($request->jumlah > $hutang->sisa_hutang) {
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah melebihi sisa hutang.']);
        }

        // Custom validation for when there is only 1 installment remaining
        if ($hutang->jumlah_cicilan == 1 && $request->jumlah != $hutang->sisa_hutang) {
            return redirect()->back()->withErrors([
                'jumlah' => 'Nominal pembayaran harus sama dengan sisa hutang karena ini adalah cicilan terakhir.'
            ]);
        }

        // Validasi tambahan untuk angsuran minimal
        if ($request->jumlah < ($hutang->sisa_hutang / $hutang->jumlah_cicilan)) {
            return redirect()->back()->withErrors([
                'jumlah' => 'Pembayaran tidak mencukupi jumlah angsuran yang ditentukan.',
            ]);
        }

        // Mengurangi sisa hutang
        $sisa_hutang = $hutang->sisa_hutang - $request->jumlah;
        $hutang->sisa_hutang = $sisa_hutang;

        if ($sisa_hutang <= 0) {
            $hutang->status = true; // Hutang lunas
        }
        $hutang->save();

        // Simpan data pembayaran
        BayarHutang::create([
            'id_hutang' => $hutang->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'nama' => $hutang->nama,
            'pembayaran' => $request->pembayaran,
            'jumlah' => $request->jumlah,
            'id_usaha' => $hutang->id_usaha,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Pembayaran hutang berhasil disimpan.');
    }
}