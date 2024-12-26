<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Beban;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('beban.kategori');
        $beban = Beban::all();
        $kategoris = Kategori::all();

        return view('beban.kategori', compact('beban', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            "nama" => "required|string|min:2|max:50|unique:kategoris,nama"
        ], [
            'nama.min' => 'Nama kategori harus memiliki minimal 2 karakter.',
            'nama.max' => 'Nama kategori tidak boleh lebih dari 50 karakter.',
            'nama.unique' => 'Nama kategori sudah ada, silakan gunakan nama lain.'
        ]);
        // dd($request->all());
        $validate['id_usaha'] = auth()->user()->id_usaha;

        Kategori::create($validate);
        return redirect()->route('beban.index')
        ->with('tambah', 'Kategori berhasil ditambahkan');;
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
