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
}
 
