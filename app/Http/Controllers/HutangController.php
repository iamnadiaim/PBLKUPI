<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use App\Models\hutang;
use App\Models\BayarHutang;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    public function index():View
    {
        $bayarhutang = BayarHutang::all();
        $hutangs = hutang::where('id_usaha', auth()->user()->id_usaha)->get(); // Ambil semua data hutang dari database
        return view('hutang.index', compact('bayarhutang','hutangs')); // Tampilkan view dengan data hutangs
    }

    public function create()
    {
        return view('hutang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jumlah_hutang' => 'required|numeric|min:1',
            'jumlah_cicilan' => 'required|numeric|min:1',
            'tanggal_pinjaman' => 'required',
            'tanggal_jatuh_tempo' => 'required',
        ]);
        $validatedData = $request->all();
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
    
        hutang::create($validatedData);
        return redirect()->route('hutang.index')
                         ->with('success', 'Hutang berhasil ditambahkan');
    }

    public function show($id)
    {
        $hutang = hutang::findOrFail($id);
        if (!$hutang) {
            return abort(404); // Handle record not found
        }
        return view('hutang.show', compact('hutang'));
    }

    public function edit($id)
    {
        $hutang = hutang::findOrFail($id);
        if (!$hutang) {
            return abort(404); // Handle record not found
        }
        return view('hutang.edit', compact('hutang'));
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'jumlah_bayar' => 'required|numeric|min:1',
    //         'sisa_hutang' => 'required|numeric|min:1',
    //     ]); // Remove validation for nama, jumlah_hutang, and tanggal

    //     $hutang = hutang::find($id); // Use find instead of findOrFail

    //     if (!$hutang) {
    //         return abort(404); // Handle record not found
    //     }

    //     $hutang->update($request->all());

    //     return redirect()->route('hutang.index')
    //         ->with('success', 'Hutang berhasil diperbarui');
    // }
}
