<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Beban;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class BebanController extends Controller
{
    public function index():View
    { 
       
        // Get all categories and beban data
        $kategoris = Kategori::where('id_usaha', Auth::user()->usaha->id)->orWhere('id_usaha', null)->get();
        // Render the view
        return view('beban.index', compact('kategoris'));

    }

    public function show($id): View
    
    {
        $beban = Beban::findOrFail($id); // Mengambil beban berdasarkan ID
        return view('beban.show', compact('beban')); // Menampilkan detail beban ke dalam view
        // return view('beban.tampilan', compact('beban'));
    }

    public function tampilan(){
        // dd("tes");
         $beban = Beban::all(); 
        return view('beban.tampilan',compact('beban'));
    }

    public function createbeban()
    {
        // Logika untuk menampilkan formulir pembuatan beban
        return view('beban.create'); // Gantilah 'beban.create' dengan nama view yang sesuai
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
        'tanggal' => 'required',
        'nama' => 'required|string|min:2|max:60',
        'id_kategori' => 'required',
        'jumlah' => 'required|integer|min:1',
        'harga' => 'required|numeric|min:1000'
    ], [
        'nama.required' => 'Nama wajib diisi.',
        'nama.min' => 'Nama harus memiliki minimal 2 karakter.',
        'nama.max' => 'Nama tidak boleh lebih dari 60 karakter.',
        'jumlah.required' => 'Jumlah wajib diisi.',
        'jumlah.integer' => 'Jumlah harus berupa angka.',
        'jumlah.min' => 'Jumlah minimal harus 1.',
        'harga.required' => 'Harga wajib diisi.',
        'harga.numeric' => 'Harga harus berupa angka.',
        'harga.min' => 'Harga minimal harus 1000.'
    ]);

        $validatedData['id_usaha'] = auth()->user()->id_usaha;
        
        // Simpan data beban baru ke dalam database
        beban::create($validatedData);

        return redirect()->route('riwayatbeban')
            ->with('success', 'pengeluaran berhasil ditambahkan'); // Redirect ke halaman detail beban dengan pesan sukses
    }
}
