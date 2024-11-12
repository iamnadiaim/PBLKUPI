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
        $hutangs = hutang::where('id_usaha', auth()->user()->id_usaha)->get(); 
        return view('hutang.index', compact('bayarhutang','hutangs')); 
    }

    public function create()
    {
        return view('hutang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:2|max:60',
            'catatan' => 'required',
            'jumlah_hutang' => 'required|numeric|min:1000|max:10000000',
            'jumlah_cicilan' => 'required|numeric|min:1',
            'tanggal_pinjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date',
        ]);
    
        $validatedData = $request->all();
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
    
        // Tambahkan nilai awal untuk sisa_hutang
        $validatedData['sisa_hutang'] = $validatedData['jumlah_hutang'];
    
        $validatedData['status'] = false;
        // dd($validatedData);
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah_hutang' => 'required|numeric|min:1',
            'sisa_hutang' => 'required|numeric|min:0',
        ]); 

        $hutang = hutang::find($id); // Use find instead of findOrFail

        if (!$hutang) {
            return abort(404); // Handle record not found
        }

        $hutang->update($request->all());

        return redirect()->route('hutang.index')
            ->with('success', 'Hutang berhasil diperbarui');
    }
}