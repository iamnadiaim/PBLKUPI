<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use App\Models\piutang;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function index():View
    {
        $piutangs = piutang::where('id_usaha', auth()->user()->id_usaha)->get(); // Ambil semua data piutang dari database
        return view('piutang.index', compact('piutangs')); // Tampilkan view dengan data piutangs
    }

    public function create()
    {
        return view('piutang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'catatan' => 'required',
            'jumlah_piutang' => 'required|numeric|min:1',
            'jumlah_cicilan' => 'required|numeric|min:1',
            'tanggal_pinjaman' => 'required',
            'tanggal_jatuh_tempo' => 'required',
        ]);
        $validatedData = $request->all();
        $validatedData['id_usaha'] = auth()->user()->id_usaha;
    
        $validatedData['sisa_piutang'] = $validatedData['jumlah_piutang'];
        // dd($validatedData);
        piutang::create($validatedData);
        
        $validatedData['status'] = false;

        return redirect()->route('piutang.index')
                         ->with('success', 'piutang berhasil ditambahkan');
    }

    public function show($id)
    {
        $piutang = piutang::findOrFail($id);
        if (!$piutang) {
            return abort(404); // Handle record not found
        }
        return view('piutang.show', compact('piutang'));
    }

    public function edit($id)
    {
        $piutang = piutang::findOrFail($id);
        if (!$piutang) {
            return abort(404); // Handle record not found
        }
        return view('piutang.edit', compact('piutang'));
    }

     public function update(Request $request, $id)
     {
         $request->validate([
             'jumlah_piutang' => 'required|numeric|min:1',
             'sisa_piutang' => 'required|numeric|min:0',
         ]); // Remove validation for nama, jumlah_piutang, and tanggal

         $piutang = piutang::find($id); // Use find instead of findOrFail

         if (!$piutang) {
             return abort(404); // Handle record not found
         }

         $piutang->update($request->all());

         return redirect()->route('piutang.index')
             ->with('success', 'piutang berhasil diperbarui');
     }
}
