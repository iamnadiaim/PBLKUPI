<?php

namespace App\Http\Controllers;

use App\Models\jenisBarang;
use App\Models\Produk;
use Illuminate\Http\Request;

class jenisBarangController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        $jenis_barangs = jenisBarang::all();

        return view('produk.jenisBarang', compact('produk', 'jenis_barangs'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
        "nama" => [
            "required|string",
            function ($attribute, $value, $fail) {
                // Periksa apakah data sudah ada
                if (jenisBarang::where('nama', $value)
                    ->where('id_usaha', auth()->user()->id_usaha)
                    ->exists()) {
                    $fail("Jenis barang sudah ada.");
                }
            }
        ],
    ], [
        "nama.required" => "Nama jenis barang harus diisi.", // Jika kosong
        "nama.string" => 'Nama jenis barang harus berupa huruf',
    ]);

        $validate['id_usaha'] = auth()->user()->id_usaha;

        jenisBarang::create($validate);
        return redirect()->route('produks.create')
            ->with('tambah', 'Jenis Barang berhasil ditambahkan');
    }
}
